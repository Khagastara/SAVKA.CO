<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShipmentDetail;

class ShipmentDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShipmentDetail::insert([
            [
                'product_quantity' => 2,
                'sub_total' => 70000,
                'shipment_id' => 1,
                'product_id' => 1
            ],
            [
                'product_quantity' => 1,
                'sub_total' => 30000,
                'shipment_id' => 1,
                'product_id' => 2
            ],
            [
                'product_quantity' => 1,
                'sub_total' => 40000,
                'shipment_id' => 2,
                'product_id' => 3
            ],
            [
                'product_quantity' => 2,
                'sub_total' => 90000,
                'shipment_id' => 2,
                'product_id' => 5
            ],
            [
                'product_quantity' => 5,
                'sub_total' => 190000,
                'shipment_id' => 3,
                'product_id' => 4
            ],
            [
                'product_quantity' => 3,
                'sub_total' => 120000,
                'shipment_id' => 3,
                'product_id' => 3
            ],
            [
                'product_quantity' => 1,
                'sub_total' => 30000,
                'shipment_id' => 4,
                'product_id' => 2
            ],
            [
                'product_quantity' => 8,
                'sub_total' => 360000,
                'shipment_id' => 5,
                'product_id' => 5
            ],
            [
                'product_quantity' => 4,
                'sub_total' => 140000,
                'shipment_id' => 6,
                'product_id' => 1
            ],
            [
                'product_quantity' => 2,
                'sub_total' => 80000,
                'shipment_id' => 6,
                'product_id' => 3
            ],
            [
                'product_quantity' => 1,
                'sub_total' => 38000,
                'shipment_id' => 6,
                'product_id' => 4
            ],
            [
                'product_quantity' => 3,
                'sub_total' => 90000,
                'shipment_id' => 7,
                'product_id' => 2
            ],
            [
                'product_quantity' => 2,
                'sub_total' => 80000,
                'shipment_id' => 8,
                'product_id' => 3
            ],
            [
                'product_quantity' => 2,
                'sub_total' => 76000,
                'shipment_id' => 8,
                'product_id' => 4
            ],
            [
                'product_quantity' => 4,
                'sub_total' => 180000,
                'shipment_id' => 9,
                'product_id' => 5
            ],
            [
                'product_quantity' => 2,
                'sub_total' => 80000,
                'shipment_id' => 9,
                'product_id' => 3
            ],
        ]);
    }
}
