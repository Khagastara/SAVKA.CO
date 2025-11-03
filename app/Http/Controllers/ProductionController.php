<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Production;
use App\Models\ProductDetail;
use App\Models\Report;

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
            $productions = Production::with(['productDetail.product', 'user'])->get();
        } elseif ($user->role === 'Production Staff') {
            $productions = Production::with(['productDetail.product'])
                ->where('user_id', $user->id)
                ->get();
        } else {
            abort(403, 'Access denied.');
        }

        return response()->json([
            'success' => true,
            'data' => $productions
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

        $validated = $request->validate([
            'production_date' => 'required|date',
            'quantity_produced' => 'required|integer|min:1',
            'material_used' => 'required|integer|min:1',
            'product_detail_id' => 'required|exists:product_details,id',
            'material_id' => 'required|exists:materials,id',
        ]);

        $productDetail = ProductDetail::with('product')->find($validated['product_detail_id']);
        $productName = $productDetail->product->product_name;
        $productSize = $productDetail->product_size;

        // Create a report automatically for this production
        $report = Report::create([
            'report_date' => $validated['production_date'],
            'description' => "Melakukan Produksi {$validated['quantity_produced']} Jilbab {$productName} {$productSize}",
            'income' => 0,
            'expenses' => 0,
        ]);

        // Create the production record
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

        return response()->json([
            'success' => true,
            'message' => 'Production schedule created successfully.',
            'data' => $production
        ]);
    }

    /**
     * Update production status.
     * When status = "Selesai", increase product stock automatically.
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $production = Production::findOrFail($id);

        if ($user->role !== 'Production Staff' && $user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Dalam Progres,Selesai',
        ]);

        $production->status = $validated['status'];
        $production->save();

        // When finished, update product stock
        if ($production->status === 'Selesai') {
            $productDetail = ProductDetail::find($production->product_detail_id);
            $productDetail->product_stock += $production->quantity_produced;
            $productDetail->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Production status updated successfully.',
            'data' => $production
        ]);
    }
}
