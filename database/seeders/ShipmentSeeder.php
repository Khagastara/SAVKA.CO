<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shipment;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shipment::insert([
            [
                'shipment_date' => '2025-10-10',
                'destination_address' => 'Jl Brawijaya No 8, Banyuwangi',
                'total_price' => 100000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 4,
                'report_id' => 6,
                'history_demand_id' => 1,
            ],
            [
                'shipment_date' => '2025-10-10',
                'destination_address' => 'Jl Gajah Mada No 15, Banyuwangi',
                'total_price' => 130000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 5,
                'report_id' => 7,
                'history_demand_id' => 1,
            ],
            [
                'shipment_date' => '2025-10-13',
                'destination_address' => 'Jl Sudirman No 20, Banyuwangi',
                'total_price' => 190000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 4,
                'report_id' => 8,
                'history_demand_id' => 2,
            ],
            [
                'shipment_date' => '2025-10-15',
                'destination_address' => 'Jl Ahmad Yani No 25, Banyuwangi',
                'total_price' => 150000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 4,
                'report_id' => 9,
                'history_demand_id' => 2,
            ],
            [
                'shipment_date' => '2025-10-15',
                'destination_address' => 'Jl Diponegoro No 30, Banyuwangi',
                'total_price' => 360000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 5,
                'report_id' => 10,
                'history_demand_id' => 2,
            ],
            [
                'shipment_date' => '2025-10-20',
                'destination_address' => 'Jl Gatot Subroto No 35, Banyuwangi',
                'total_price' => 220000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 4,
                'report_id' => 11,
                'history_demand_id' => 3,
            ],
            [
                'shipment_date' => '2025-10-24',
                'destination_address' => 'Jl Pahlawan No 40, Banyuwangi',
                'total_price' => 180000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 5,
                'report_id' => 12,
                'history_demand_id' => 3,
            ],
            [
                'shipment_date' => '2025-10-28',
                'destination_address' => 'Jl Merdeka No 45, Banyuwangi',
                'total_price' => 156000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 4,
                'report_id' => 13,
                'history_demand_id' => 4,
            ],
            [
                'shipment_date' => '2025-10-30',
                'destination_address' => 'Jl Kemerdekaan No 50, Banyuwangi',
                'total_price' => 260000,
                'shipment_status' => 'Sampai Tujuan',
                'user_id' => 5,
                'report_id' => 14,
                'history_demand_id' => 4,
            ]
        ]);
    }
}
