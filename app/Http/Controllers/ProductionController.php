<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Production;
use App\Models\ProductDetail;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionController extends Controller
{
    /**
     * Display production schedules.
     * Owner = see all
     * Production Staff = see their own
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'Owner') {
            $productions = Production::with(['productDetail.product', 'user'])
            ->orderBy('production_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        } elseif ($user->role === 'Production Staff') {
            $productions = Production::with(['productDetail.product', 'material'])
                ->orderBy('production_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->where('user_id', $user->id)
                ->paginate(10);
        } else {
            abort(403, 'Access denied.');
        }

        return response()->json([
            'success' => true,
            'data' => $productions->items(),
            'pagination' => [
                'current_page' => $productions->currentPage(),
                'last_page' => $productions->lastPage(),
                'per_page' => $productions->perPage(),
                'total' => $productions->total(),
            ]
        ]);
    }

    /**
     * Create new production schedule (Production Staff).
     * Default status = "Dalam Progres"
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Production Staff') {
            abort(403, 'Access denied.');
        }

        Log::info('Request data:', $request->all()); // Log data request

        try {
            $validated = $request->validate([
                'production_date' => 'required|date',
                'quantity_produced' => 'required|integer|min:1',
                'material_used' => 'required|integer|min:1',
                'product_detail_id' => 'required|exists:product_details,id',
                'material_id' => 'required|exists:materials,id',
            ]);

            Log::info('Validated data:', $validated); // Log data yang sudah divalidasi

            $productDetail = ProductDetail::with('product')->find($validated['product_detail_id']);
            Log::info('Product detail:', ['productDetail' => $productDetail]);

            $productName = $productDetail->product->product_name ?? 'Produk';
            $productColor = $productDetail->product->product_color ?? '-';
            $productSize = $productDetail->product_size ?? '-';
            $description = "Memproduksi {$validated['quantity_produced']} buah {$productName} {$productColor} (Ukuran: {$productSize})";

            $report = Report::create([
                'report_date' => $validated['production_date'],
                'description' => $description,
                'income' => 0,
                'expenses' => 0,
            ]);

            Log::info('Report created:', ['report' => $report]);

            $production = Production::create([
                'production_date' => $validated['production_date'],
                'quantity_produced' => $validated['quantity_produced'],
                'material_used' => $validated['material_used'],
                'status' => 'Dalam Progres',
                'user_id' => $user->id,
                'product_detail_id' => $validated['product_detail_id'],
                'material_id' => $validated['material_id'],
                'report_id' => $report->id,
            ]);

            Log::info('Production created:', ['production' => $production]);

            return response()->json([
                'success' => true,
                'message' => 'Production schedule created successfully.',
                'data' => $production
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan jadwal produksi:', ['exception' => $e->getMessage()]); // Log error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan jadwal produksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update production status.
     * When status = "Selesai", increase product stock automatically.
     */
    public function updateStatus($id, Request $request)
    {
        Log::info('Update Status Request:', [
            'production_id' => $id,
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role
        ]);

        try {
            $production = Production::with(['productDetail.product', 'material'])->find($id);

            if (!$production) {
                Log::error('Production not found:', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal produksi tidak ditemukan.'
                ], 404);
            }

            // Check if user has permission
            $user = Auth::user();
            if ($user->role === 'Production Staff' && $production->user_id !== $user->id) {
                Log::warning('Unauthorized access attempt:', [
                    'user_id' => $user->id,
                    'production_user_id' => $production->user_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah jadwal produksi ini.'
                ], 403);
            }

            $status = $request->input('status');
            Log::info('Updating status to:', ['status' => $status]);

            if (!in_array($status, ['Dalam Progres', 'Selesai'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid. Hanya "Dalam Progres" atau "Selesai" yang diperbolehkan.'
                ]);
            }

            DB::beginTransaction();

            // Update production status
            $production->status = $status;
            $production->save();

            Log::info('Production status updated:', [
                'production_id' => $production->id,
                'new_status' => $production->status
            ]);

            // If status is "Selesai", update product stock and material stock
            if ($status === 'Selesai') {
                Log::info('Processing completion for production:', [
                    'production_id' => $production->id,
                    'product_detail_id' => $production->product_detail_id,
                    'quantity_produced' => $production->quantity_produced,
                    'material_id' => $production->material_id,
                    'material_used' => $production->material_used
                ]);

                // Update product stock
                $productDetail = ProductDetail::find($production->product_detail_id);
                if ($productDetail) {
                    $oldStock = $productDetail->product_stock;
                    $productDetail->product_stock += $production->quantity_produced;
                    $productDetail->save();

                    Log::info('Product stock updated:', [
                        'product_detail_id' => $productDetail->id,
                        'old_stock' => $oldStock,
                        'new_stock' => $productDetail->product_stock,
                        'added_quantity' => $production->quantity_produced
                    ]);
                } else {
                    Log::error('Product detail not found:', [
                        'product_detail_id' => $production->product_detail_id
                    ]);
                    throw new \Exception('Detail produk tidak ditemukan.');
                }

                // Update material stock - PERBAIKAN DI SINI
                $material = $production->material;
                if ($material) {
                    $oldMaterialStock = $material->material_quantity;

                    // Handle null stock - treat as 0
                    $currentStock = $material->material_quantity ?? 0;

                    Log::info('Material stock check:', [
                        'material_id' => $material->id,
                        'material_name' => $material->material_name,
                        'current_stock' => $currentStock,
                        'required_stock' => $production->material_used
                    ]);

                    // Check if material stock is sufficient
                    if ($currentStock < $production->material_used) {
                        Log::error('Insufficient material stock:', [
                            'material_id' => $material->id,
                            'current_stock' => $currentStock,
                            'required_stock' => $production->material_used
                        ]);
                        throw new \Exception("Stok material {$material->material_name} tidak cukup. Stok tersedia: {$currentStock}, dibutuhkan: {$production->material_used}");
                    }

                    // Update material stock - handle null case
                    $material->material_quantity = $currentStock - $production->material_used;
                    $material->save();

                    Log::info('Material stock updated:', [
                        'material_id' => $material->id,
                        'material_name' => $material->material_name,
                        'old_stock' => $oldMaterialStock,
                        'new_stock' => $material->material_quantity,
                        'used_quantity' => $production->material_used
                    ]);
                } else {
                    Log::error('Material not found:', [
                        'material_id' => $production->material_id
                    ]);
                    throw new \Exception('Material tidak ditemukan.');
                }

                // Update report
                $report = $production->report;
                if ($report) {
                    $report->description = "Produksi selesai: {$production->quantity_produced} unit {$productDetail->product->product_name} ({$productDetail->product_size})";
                    $report->save();
                    Log::info('Report updated:', ['report_id' => $report->id]);
                }
            }

            DB::commit();

            Log::info('Production completion processed successfully:', [
                'production_id' => $production->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status produksi berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating production status:', [
                'production_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }
}
