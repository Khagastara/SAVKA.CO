<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Production;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Production::insert([
            [
                'production_date' => '2025-10-6',
                'quantity_produced' => 5,
                'material_used' => 10,
                'status' => 'Selesai',
                'user_id' => 2,
                'product_detail_id' => 1,
                'material_id' => 1,
                'report_id' => 3,
            ],
            [
                'production_date' => '2025-10-6',
                'quantity_produced' => 10,
                'material_used' => 20,
                'status' => 'Selesai',
                'user_id' => 3,
                'product_detail_id' => 5,
                'material_id' => 2,
                'report_id' => 4,
            ],
            [
                'production_date' => '2025-10-13',
                'quantity_produced' => 8,
                'material_used' => 16,
                'status' => 'Selesai',
                'user_id' => 2,
                'product_detail_id' => 8,
                'material_id' => 3,
                'report_id' => 5,
            ]
        ]);
    }
}
