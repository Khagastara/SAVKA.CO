<?php

namespace App\Http\Controllers;

use App\Models\Forecasting;
use App\Models\HistoryDemand;
use App\Models\Product;
use App\Models\ProductDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForecastingController extends Controller
{
    /**
     * Menampilkan semua hasil forecast
     */
    public function index()
    {
        $forecasts = Forecasting::with(['historyDemand'])->latest()->get();

        return response()->json([
            'message' => 'Data forecasting berhasil diambil.',
            'data' => $forecasts
        ]);
    }

    /**
     * Melakukan perhitungan Single Moving Average untuk setiap produk
     */
    public function calculate(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json(['message' => 'Hanya owner yang dapat melakukan forecasting.'], 403);
        }

        $validated = $request->validate([
            'week_used' => 'required|integer|min:2|max:6',
        ]);

        $weekUsed = $validated['week_used'];

        // Get all product details with product info
        $productDetails = ProductDetail::with('product')->get();
        $forecastResults = [];
        $processedCount = 0;
        $skippedProducts = [];

        foreach ($productDetails as $detail) {
            // Get historical data using raw query builder
            $historicalData = DB::table('shipment_details')
                ->join('shipments', 'shipment_details.shipment_id', '=', 'shipments.id')
                ->join('history_demands', 'shipments.history_demand_id', '=', 'history_demands.id')
                ->where('shipment_details.product_detail_id', $detail->id)
                ->select(
                    'history_demands.id as history_id',
                    'history_demands.year',
                    'history_demands.month',
                    'history_demands.week_number',
                    'shipment_details.product_quantity'
                )
                ->orderBy('history_demands.year', 'desc')
                ->orderBy('history_demands.month', 'desc')
                ->orderBy('history_demands.week_number', 'desc')
                ->limit($weekUsed)
                ->get();

            $dataCount = $historicalData->count();

            // Skip if not enough data, but track it
            if ($dataCount < 2) { // Minimal 2 data point untuk prediksi
                $skippedProducts[] = [
                    'product' => $detail->product->product_name ?? 'Unknown',
                    'color' => $detail->product->product_color ?? 'Unknown',
                    'size' => $detail->product_size ?? 'Unknown',
                    'data_available' => $dataCount,
                    'data_needed' => $weekUsed,
                    'reason' => 'Minimal 2 data historis diperlukan'
                ];
                continue;
            }

            // If data count is less than requested, use available data
            $actualWeeksUsed = min($dataCount, $weekUsed);

            // Calculate Single Moving Average
            $totalDemand = $historicalData->sum('product_quantity');
            $averageDemand = $totalDemand / $actualWeeksUsed;
            $predictedDemand = round($averageDemand);

            // Get latest data for accuracy calculation
            $latestData = $historicalData->first();
            $actualDemand = $latestData->product_quantity;

            // Calculate accuracy (MAPE - Mean Absolute Percentage Error)
            if ($actualDemand > 0) {
                $accuracy = max(0, round(100 - abs(($predictedDemand - $actualDemand) / $actualDemand * 100)));
            } else {
                $accuracy = 0;
            }

            // Get forecast date (next month from latest data)
            $forecastDate = Carbon::create($latestData->year, $latestData->month)
                ->addMonth()
                ->endOfMonth();

            // Save to database
            try {
                $forecast = Forecasting::create([
                    'forecast_date' => $forecastDate,
                    'week_used' => $actualWeeksUsed,
                    'predicted_demand' => $predictedDemand,
                    'accurancy' => $accuracy,
                    'history_demand_id' => $latestData->history_id,
                ]);

                $forecastResults[] = [
                    'product' => $detail->product->product_name ?? 'Unknown',
                    'product_color' => $detail->product->product_color ?? 'Unknown',
                    'size' => $detail->product_size ?? 'Unknown',
                    'predicted_demand' => $predictedDemand,
                    'accuracy' => $accuracy,
                    'forecast_date' => $forecastDate->format('d M Y'),
                    'weeks_analyzed' => $actualWeeksUsed,
                    'total_demand' => $totalDemand,
                    'average_demand' => round($averageDemand, 2),
                    'note' => $actualWeeksUsed < $weekUsed ? "Hanya {$actualWeeksUsed} minggu data tersedia" : null
                ];

                $processedCount++;

                Log::info("Forecast created for product detail {$detail->id} with {$actualWeeksUsed} weeks");
            } catch (\Exception $e) {
                Log::error("Error creating forecast for product detail {$detail->id}: " . $e->getMessage());
            }
        }

        // Prepare response based on results
        if (empty($forecastResults)) {
            return response()->json([
                'message' => "Tidak dapat melakukan forecasting. Tidak ada produk dengan minimal 2 data historis.",
                'data' => [],
                'skipped_products' => $skippedProducts,
                'summary' => [
                    'total_products' => $productDetails->count(),
                    'processed' => 0,
                    'skipped' => count($skippedProducts),
                    'suggestion' => 'Tambahkan lebih banyak data pengiriman untuk setiap produk'
                ]
            ], 400);
        }

        return response()->json([
            'message' => "Forecasting selesai! Berhasil menganalisis {$processedCount} varian produk.",
            'data' => $forecastResults,
            'skipped_products' => $skippedProducts,
            'summary' => [
                'total_products' => $productDetails->count(),
                'processed' => $processedCount,
                'skipped' => count($skippedProducts),
                'weeks_requested' => $weekUsed,
                'average_accuracy' => $processedCount > 0
                    ? round(array_sum(array_column($forecastResults, 'accuracy')) / $processedCount, 2)
                    : 0
            ]
        ]);
    }
}
