<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_color' => 'required|string|max:100',
            'product_price' => 'required|integer|min:0',
            'details' => 'required|array|min:1',
            'details.*.product_size' => 'required|in:S,M,L',
            'details.*.product_stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create product
            $product = Product::create([
                'product_name' => $validated['product_name'],
                'product_color' => $validated['product_color'],
                'product_price' => $validated['product_price'],
            ]);

            // Create product details
            foreach ($validated['details'] as $detail) {
                ProductDetail::create([
                    'product_size' => $detail['product_size'],
                    'product_stock' => $detail['product_stock'],
                    'product_id' => $product->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan.',
                'data' => $product->load('productDetail')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing product (Owner only).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_color' => 'required|string|max:100',
            'product_price' => 'required|integer|min:0',
            'details' => 'required|array|min:1',
            'details.*.id' => 'nullable|integer|exists:product_details,id',
            'details.*.product_size' => 'required|in:S,M,L',
            'details.*.product_stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Update product basic info
            $product->update([
                'product_name' => $validated['product_name'],
                'product_color' => $validated['product_color'],
                'product_price' => $validated['product_price'],
            ]);

            // Collect IDs of details that should be kept
            $detailIdsToKeep = [];

            // Update or create product details
            foreach ($validated['details'] as $detail) {
                if (isset($detail['id']) && $detail['id']) {
                    // Update existing detail
                    $productDetail = ProductDetail::where('id', $detail['id'])
                        ->where('product_id', $product->id)
                        ->first();

                    if ($productDetail) {
                        $productDetail->update([
                            'product_size' => $detail['product_size'],
                            'product_stock' => $detail['product_stock'],
                        ]);
                        $detailIdsToKeep[] = $productDetail->id;
                    }
                } else {
                    // Create new detail
                    $newDetail = ProductDetail::create([
                        'product_size' => $detail['product_size'],
                        'product_stock' => $detail['product_stock'],
                        'product_id' => $product->id,
                    ]);
                    $detailIdsToKeep[] = $newDetail->id;
                }
            }

            // Delete details that were removed
            ProductDetail::where('product_id', $product->id)
                ->whereNotIn('id', $detailIdsToKeep)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.',
                'data' => $product->load('productDetail')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage()
            ], 500);
        }
    }
}
