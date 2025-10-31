<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Report;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Report::insert([
            [
                'report_date' => '2025-10-21',
                'description' => 'Pembelian Bahan Baku',
                'income' => 0,
                'expenses' => 500000,
            ],
            [
                'report_date' => '2024-01-23',
                'description' => 'Pembelian Bahan Baku',
                'income' => 0,
                'expenses' => 300000,
            ],
            [
                'report_date' => '2025-10-6',
                'description' => 'Produksi 5 Jilbab Voal S ',
                'income' => 0,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-6',
                'description' => 'Produksi 10 Jilbab Katun M',
                'income' => 0,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-13',
                'description' => 'Produksi 8 Jilbab Chiffon L',
                'income' => 0,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-10',
                'description' => 'Penjualan Jl Brawijaya No 8, Banyuwangi',
                'income' => 100000,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-10',
                'description' => 'Penjualan Jl Gajah Mada No 15, Banyuwangi',
                'income' => 130000,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-13',
                'description' => 'Penjualan Jl Sudirman No 20, Banyuwangi',
                'income' => 190000,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-15',
                'description' => 'Penjualan Jl Ahmad Yani No 25, Banyuwangi',
                'income' => 150000,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-15',
                'description' => 'Penjualan Jl Diponegoro No 30, Banyuwangi',
                'income' => 360000,
                'expenses' => 0,
            ],
            [
                'report_date' => '2025-10-20',
                'description' => 'Penjualan Jl Gatot Subroto No 35, Banyuwangi',
                'income' => 220000,
                'expenses' => 0,
            ]
        ]);
    }
}
