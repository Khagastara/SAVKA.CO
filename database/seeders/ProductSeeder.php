<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'product_name' => 'Jilbab Voal',
                'product_color' => 'Cream',
                'product_price' => 35000,
            ],
            [
                'product_name' => 'Jilbab Katun',
                'product_color' => 'Putih',
                'product_price' => 30000,
            ],
            [
                'product_name' => 'Jilbab Chiffon',
                'product_color' => 'Abu-Abu',
                'product_price' => 40000,
            ],
            [
                'product_name' => 'Jilbab Rayon',
                'product_color' => 'Dusty Pink',
                'product_price' => 38000,
            ],
            [
                'product_name' => 'Jilbab Satin',
                'product_color' => 'Navy Blue',
                'product_price' => 45000,
            ],
        ]);
    }
}
