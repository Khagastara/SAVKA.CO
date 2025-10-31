<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductDetail;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductDetail::insert([
            [
                'product_size' => 'S',
                'product_stock' => 20,
                'product_id' => 1,
            ],
            [
                'product_size' => 'M',
                'product_stock' => 20,
                'product_id' => 1,
            ],
            [
                'product_size' => 'L',
                'product_stock' => 20,
                'product_id' => 1,
            ],
            [
                'product_size' => 'S',
                'product_stock' => 20,
                'product_id' => 2,
            ],
            [
                'product_size' => 'M',
                'product_stock' => 20,
                'product_id' => 2,
            ],
            [
                'product_size' => 'L',
                'product_stock' => 15,
                'product_id' => 2,
            ],
            [
                'product_size' => 'S',
                'product_stock' => 20,
                'product_id' => 3,
            ],
            [
                'product_size' => 'M',
                'product_stock' => 20,
                'product_id' => 3,
            ],
            [
                'product_size' => 'L',
                'product_stock' => 20,
                'product_id' => 3,
            ],
            [
                'product_size' => 'S',
                'product_stock' => 10,
                'product_id' => 4,
            ],
            [
                'product_size' => 'M',
                'product_stock' => 20,
                'product_id' => 4,
            ],
            [
                'product_size' => 'L',
                'product_stock' => 10,
                'product_id' => 4,
            ],
            [
                'product_size' => 'S',
                'product_stock' => 20,
                'product_id' => 5,
            ],
            [
                'product_size' => 'M',
                'product_stock' => 20,
                'product_id' => 5,
            ],
            [
                'product_size' => 'L',
                'product_stock' => 20,
                'product_id' => 5,
            ],
        ]);
    }
}
