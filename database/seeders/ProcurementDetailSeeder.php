<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProcurementDetail;

class ProcurementDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProcurementDetail::insert([
            [
                'quantity' => 20,
                'procurement_id' => 1,
                'material_id' => 1,
            ],
            [
                'quantity' => 20,
                'procurement_id' => 1,
                'material_id' => 2,
            ],
            [
                'quantity' => 20,
                'procurement_id' => 1,
                'material_id' => 2,
            ],
            [
                'quantity' => 20,
                'procurement_id' => 2,
                'material_id' => 3,
            ],
            [
                'quantity' => 10,
                'procurement_id' => 2,
                'material_id' => 4,
            ],
        ]);
    }
}
