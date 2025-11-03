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

class ForecastingController extends Controller
{
    /**
     * Menampilkan semua hasil forecast
     */
    public function index()
    {
        $forecasts = Forecasting::with('historyDemand')->latest()->get();

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

        $products = Product::with('productDetail')->get();
        $forecastResults = [];

        foreach ($products as $product) {
            foreach ($product->productDetail as $detail) {
                $history = HistoryDemand::whereHas('shipment.shipmentDetail', function ($q) use ($product, $detail) {
                    $q->where('product_id', $product->id);
                })
                ->orderBy('year')
                ->orderBy('month')
                ->orderBy('week_number')
                ->take($weekUsed)
                ->get();

                if ($history->count() < $weekUsed) {
                    continue;
                }

                $predictedDemand = round($history->avg('demand_quantity'));
                $latest = $history->last();
                $forecastDate = Carbon::create($latest->year, $latest->month)->endOfMonth();
                $actual = $latest->demand_quantity;
                $accuracy = $actual != 0
                    ? round(100 - abs(($predictedDemand - $actual) / $actual * 100))
                    : 0;

                $forecast = Forecasting::create([
                    'forecast_date' => $forecastDate,
                    'week_used' => $weekUsed,
                    'predicted_demand' => $predictedDemand,
                    'accurancy' => $accuracy,
                    'history_demand_id' => $latest->id,
                ]);

                $forecastResults[] = [
                    'product' => $product->product_name,
                    'size' => $detail->product_size,
                    'predicted_demand' => $predictedDemand,
                    'accuracy' => $accuracy,
                ];
            }
        }

        return response()->json([
            'message' => 'Forecasting selesai dilakukan.',
            'data' => $forecastResults,
        ]);
    }
}
