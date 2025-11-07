<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductDetail;

class ProductController extends Controller
{
    /**
     * View all products and their details.
     */
    public function index()
    {
        $user = Auth::user();

        // if (!in_array($user->role, ['Owner', 'Production Staff'])) {
        //     abort(403, 'Access denied.');
        // }

        $products = Product::with('productDetail')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Create a new product (Owner only).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_color' => 'required|string|max:100',
            'product_price' => 'required|integer|min:0',
            'details' => 'required|array',
            'details.*.product_size' => 'required|in:S,M,L',
            'details.*.product_stock' => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'product_name' => $validated['product_name'],
            'product_color' => $validated['product_color'],
            'product_price' => $validated['product_price'],
        ]);

        foreach ($validated['details'] as $detail) {
            ProductDetail::create([
                'product_size' => $detail['product_size'],
                'product_stock' => $detail['product_stock'],
                'product_id' => $product->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product->load('productDetail')
        ]);
    }

    /**
     * Update existing product (Owner only).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_name' => 'sometimes|string|max:255',
            'product_color' => 'sometimes|string|max:100',
            'product_price' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $product
        ]);
    }
}
