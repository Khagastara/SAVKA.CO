<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HistoryDemand;

class HistoryDemandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistoryDemand::insert([
            [
                'week_number' => 2,
                'month' => 10,
                'year' => 2025,
                'demand_quantity' => 6,
            ],
            [
                'week_number' => 3,
                'month' => 10,
                'year' => 2025,
                'demand_quantity' => 17,
            ],
            [
                'week_number' => 4,
                'month' => 10,
                'year' => 2025,
                'demand_quantity' => 9,
            ],
            [
                'week_number' => 5,
                'month' => 10,
                'year' => 2025,
                'demand_quantity' => 6,
            ],
        ]);
    }
}
