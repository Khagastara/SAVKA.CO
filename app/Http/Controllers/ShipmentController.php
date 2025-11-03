<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Shipment;
use App\Models\ShipmentDetail;
use App\Models\Product;
use App\Models\HistoryDemand;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShipmentController extends Controller
{
    /**
     * Menampilkan semua data pengiriman
     * - Owner: lihat semua
     * - Staf Distribusi: lihat hanya miliknya sendiri
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'Owner') {
            $shipments = Shipment::with(['shipmentDetail.product', 'user', 'report'])->get();
        } else {
            $shipments = Shipment::with(['shipmentDetail.product', 'user', 'report'])
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json($shipments);
    }

    /**
     * Membuat data pengiriman baru (khusus staf distribusi)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Distribution Staff') {
            return response()->json(['message' => 'Hanya staf distribusi yang dapat membuat pengiriman.'], 403);
        }

        $validated = $request->validate([
            'shipment_date' => 'required|date',
            'destination_address' => 'required|string',
            'total_price' => 'required|integer',
            'product_details' => 'required|array',
        ]);

        $shipmentDate = Carbon::parse($validated['shipment_date']);
        $weekNumber = $shipmentDate->weekOfMonth;
        $month = $shipmentDate->month;
        $year = $shipmentDate->year;

        $totalDemand = collect($validated['product_details'])->sum('quantity');

        $historyDemand = HistoryDemand::where('week_number', $weekNumber)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($historyDemand) {
            $historyDemand->increment('demand_quantity', $totalDemand);
        } else {
            $historyDemand = HistoryDemand::create([
                'week_number' => $weekNumber,
                'month' => $month,
                'year' => $year,
                'demand_quantity' => $totalDemand,
            ]);
        }

        $report = Report::create([
            'report_date' => $shipmentDate,
            'description' => "Melakukan Pengiriman ke {$validated['destination_address']}",
            'income' => $validated['total_price'],
            'expenses' => 0,
        ]);

        $shipment = Shipment::create([
            'shipment_date' => $shipmentDate,
            'destination_address' => $validated['destination_address'],
            'total_price' => $validated['total_price'],
            'shipment_status' => 'Dalam Pengiriman',
            'user_id' => $user->id,
            'report_id' => $report->id,
            'history_demand_id' => $historyDemand->id,
        ]);

        foreach ($validated['product_details'] as $detail) {
            ShipmentDetail::create([
                'shipment_id' => $shipment->id,
                'product_id' => $detail['product_id'],
                'product_quantity' => $detail['quantity'],
                'sub_total' => $detail['sub_total'],
            ]);
        }

        return response()->json([
            'message' => 'Data pengiriman berhasil dibuat.',
            'shipment' => $shipment->load('shipmentDetail.product')
        ]);
    }

    /**
     * Mengubah data pengiriman (khusus staf distribusi)
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
            'total_price' => 'sometimes|integer',
            'shipment_status' => 'sometimes|in:Dalam Pengiriman,Sampai Tujuan',
        ]);

        $shipment->update($validated);

        return response()->json(['message' => 'Data pengiriman berhasil diperbarui.', 'shipment' => $shipment]);
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

        return response()->json(['message' => 'Status pengiriman telah diperbarui menjadi Sampai Tujuan.']);
    }
}
