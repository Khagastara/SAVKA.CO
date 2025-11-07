<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Material;
use App\Models\Procurement;
use App\Models\ProcurementDetail;
use App\Models\Report;

class SupplyController extends Controller
{
    /**
     * Display all materials (for Owner and Production Staff).
     */
    public function index()
    {
        $materials = Material::paginate(10);

        return response()->json([
            'success' => true,
            'data' => $materials->items(),
            'pagination' => [
                'current_page' => $materials->currentPage(),
                'last_page' => $materials->lastPage(),
                'per_page' => $materials->perPage(),
                'total' => $materials->total(),
            ],
        ]);
    }


    /**
     * Add new material (Owner only).
     */
    public function storeMaterial(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'material_name' => 'required|string|max:255',
            'material_color' => 'required|string|max:100',
            'material_quantity' => 'required|integer|min:0',
        ]);

        $material = Material::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'New material added successfully.',
            'data' => $material
        ]);
    }

    /**
     * Create procurement (Owner only).
     * - Owner buys existing materials
     * - Automatically creates procurement details and a report
     */
    public function storeProcurement(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'procurement_date' => 'required|date',
            'total_cost' => 'required|integer|min:1',
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
        ]);

        // Create a report
        $report = Report::create([
            'report_date' => $validated['procurement_date'],
            'description' => 'Procurement Report for ' . $validated['procurement_date'],
            'income' => 0,
            'expenses' => $validated['total_cost'],
        ]);

        // Create a procurement linked to the report
        $procurement = Procurement::create([
            'procurement_date' => $validated['procurement_date'],
            'total_cost' => $validated['total_cost'],
            'user_id' => $user->id,
            'report_id' => $report->id,
        ]);

        // Create procurement details
        foreach ($validated['materials'] as $item) {
            ProcurementDetail::create([
                'procurement_id' => $procurement->id,
                'material_id' => $item['material_id'],
                'quantity' => $item['quantity'],
            ]);

            // Update material stock
            $material = Material::find($item['material_id']);
            $material->material_quantity += $item['quantity'];
            $material->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Procurement recorded successfully and report created.',
            'data' => [
                'report' => $report,
                'procurement' => $procurement,
            ]
        ]);
    }

    /**
     * Update existing material (Owner only).
     */
    public function updateMaterial(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'material_name' => 'sometimes|string|max:255',
            'material_color' => 'sometimes|string|max:100',
            'material_quantity' => 'sometimes|integer|min:0',
        ]);

        $material->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Material updated successfully.',
            'data' => $material
        ]);
    }
}
