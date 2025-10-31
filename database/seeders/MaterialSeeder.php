<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Material::insert([
            [
                [
                    'material_name' => 'Kain Voal',
                    'material_color' => 'Cream',
                    'material_quantity' => 150,
                ],
                [
                    'material_name' => 'Kain Katun',
                    'material_color' => 'Putih',
                    'material_quantity' => 200,
                ],
                [
                    'material_name' => 'Kain Chiffon',
                    'material_color' => 'Abu-Abu',
                    'material_quantity' => 120,
                ],
                [
                    'material_name' => 'Kain Polyester',
                    'material_color' => 'Hitam',
                    'material_quantity' => 180,
                ],
                [
                    'material_name' => 'Kain Rayon',
                    'material_color' => 'Dusty Pink',
                    'material_quantity' => 100,
                ],
                [
                    'material_name' => 'Kain Ceruty',
                    'material_color' => 'Coklat Muda',
                    'material_quantity' => 130,
                ],
                [
                    'material_name' => 'Kain Satin',
                    'material_color' => 'Biru Dongker',
                    'material_quantity' => 90,
                ],
            ]
        ]);
    }
}
