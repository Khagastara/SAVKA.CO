<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Procurement;

class ProcurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Procurement::insert([
            [
                'procurement_date' => '2025-10-21',
                'total_cost' => 500000,
                'user_id' => 1,
                'report_id' => 1,
            ],
            [
                'procurement_date' => '2024-01-23',
                'total_cost' => 300000,
                'user_id' => 1,
                'report_id' => 2,
            ]
        ]);
    }
}
