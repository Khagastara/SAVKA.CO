<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Shipment;
use App\Models\ShipmentDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\HistoryDemand;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ShipmentController extends Controller
{
    /**
     * Menampilkan semua data pengiriman
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'Owner') {
            $shipments = Shipment::with(['shipmentDetails.productDetail.product', 'user', 'report'])
                ->orderBy('shipment_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $shipments = Shipment::with(['shipmentDetails.productDetail.product', 'user', 'report'])
                ->where('user_id', $user->id)
                ->orderBy('shipment_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return response()->json($shipments);
    }

    /**
     * Membuat data pengiriman baru
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Distribution Staff') {
            return response()->json(['message' => 'Hanya staf distribusi yang dapat membuat pengiriman.'], 403);
        }

        try {
            // Validasi input
            $validated = $request->validate([
                'shipment_date' => 'required|date',
                'destination_address' => 'required|string',
                'total_price' => 'required|integer|min:0',
                'product_details' => 'required|array|min:1',
                'product_details.*.product_id' => 'required|exists:products,id',
                'product_details.*.product_size' => 'required|string',
                'product_details.*.quantity' => 'required|integer|min:1',
                'product_details.*.sub_total' => 'required|integer|min:0',
            ]);

            // Log untuk debugging
            Log::info('Creating shipment', ['data' => $validated, 'user_id' => $user->id]);

            // Validasi stok SEBELUM melakukan transaksi
            foreach ($validated['product_details'] as $detail) {
                $productDetail = ProductDetail::whereHas('product', function ($query) use ($detail) {
                    $query->where('id', $detail['product_id']);
                })
                ->where('product_size', $detail['product_size'])
                ->first();

                if (!$productDetail) {
                    return response()->json([
                        'message' => "Produk dengan ID {$detail['product_id']} dan ukuran {$detail['product_size']} tidak ditemukan."
                    ], 404);
                }

                if ($productDetail->product_stock < $detail['quantity']) {
                    return response()->json([
                        'message' => "Stok tidak mencukupi untuk produk {$productDetail->product->product_name} ukuran {$productDetail->product_size}. Stok tersedia: {$productDetail->product_stock}, diminta: {$detail['quantity']}"
                    ], 400);
                }
            }

            // Gunakan database transaction
            DB::beginTransaction();

            try {
                $shipmentDate = Carbon::parse($validated['shipment_date']);
                $weekNumber = $shipmentDate->weekOfMonth;
                $month = $shipmentDate->month;
                $year = $shipmentDate->year;

                $totalDemand = collect($validated['product_details'])->sum('quantity');

                // Update atau create history demand
                $historyDemand = HistoryDemand::firstOrCreate(
                    [
                        'week_number' => $weekNumber,
                        'month' => $month,
                        'year' => $year,
                    ],
                    ['demand_quantity' => 0]
                );

                $historyDemand->increment('demand_quantity', $totalDemand);

                // Create report
                $report = Report::create([
                    'report_date' => $shipmentDate,
                    'description' => "Melakukan Pengiriman ke {$validated['destination_address']}",
                    'income' => $validated['total_price'],
                    'expenses' => 0,
                ]);

                // Create shipment
                $shipment = Shipment::create([
                    'shipment_date' => $shipmentDate,
                    'destination_address' => $validated['destination_address'],
                    'total_price' => $validated['total_price'],
                    'shipment_status' => 'Dalam Pengiriman',
                    'user_id' => $user->id,
                    'report_id' => $report->id,
                    'history_demand_id' => $historyDemand->id,
                ]);

                // Create shipment details dan update stok
                foreach ($validated['product_details'] as $detail) {
                    $productDetail = ProductDetail::whereHas('product', function ($query) use ($detail) {
                        $query->where('id', $detail['product_id']);
                    })
                    ->where('product_size', $detail['product_size'])
                    ->lockForUpdate() // Lock row untuk menghindari race condition
                    ->first();

                    // Double check stok dalam transaction
                    if ($productDetail->product_stock < $detail['quantity']) {
                        throw new \Exception("Stok tidak mencukupi untuk {$productDetail->product->product_name} ukuran {$productDetail->product_size}");
                    }

                    ShipmentDetail::create([
                        'shipment_id' => $shipment->id,
                        'product_detail_id' => $productDetail->id,
                        'product_quantity' => $detail['quantity'],
                        'sub_total' => $detail['sub_total'],
                    ]);

                    $productDetail->decrement('product_stock', $detail['quantity']);
                }

                DB::commit();

                // Load relasi untuk response
                $shipment->load('shipmentDetails.productDetail.product');

                return response()->json([
                    'message' => 'Data pengiriman berhasil dibuat.',
                    'shipment' => $shipment
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to create shipment', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating shipment', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Gagal membuat pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengubah data pengiriman
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $shipment = Shipment::findOrFail($id);

        if ($user->role !== 'Distribution Staff' || $shipment->shipment_status !== 'Dalam Pengiriman') {
            return response()->json(['message' => 'Tidak dapat mengubah data ini.'], 403);
        }

        $validated = $request->validate([
            'destination_address' => 'sometimes|string',
            'total_price' => 'sometimes|integer|min:0',
            'shipment_status' => 'sometimes|in:Dalam Pengiriman,Sampai Tujuan',
        ]);

        $shipment->update($validated);

        return response()->json([
            'message' => 'Data pengiriman berhasil diperbarui.',
            'shipment' => $shipment
        ]);
    }

    /**
     * Menandai pengiriman sudah sampai tujuan
     */
    public function markAsDelivered($id)
    {
        $shipment = Shipment::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'Distribution Staff') {
            return response()->json(['message' => 'Hanya staf distribusi yang dapat memperbarui status.'], 403);
        }

        $shipment->update(['shipment_status' => 'Sampai Tujuan']);

        return response()->json([
            'message' => 'Status pengiriman telah diperbarui menjadi Sampai Tujuan.',
            'shipment' => $shipment
        ]);
    }
}
