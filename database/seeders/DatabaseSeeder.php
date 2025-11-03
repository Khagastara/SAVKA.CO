<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MaterialSeeder::class,
            ReportSeeder::class,
            ProcurementSeeder::class,
            ProcurementDetailSeeder::class,
            ProductSeeder::class,
            ProductDetailSeeder::class,
            ProductionSeeder::class,
            ReportSeeder::class,
            HistoryDemandSeeder::class,
            ShipmentSeeder::class,
            ShipmentDetailSeeder::class,
        ]);
    }
}
