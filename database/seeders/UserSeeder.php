<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'username' => 'admin123',
                'admin' => 'adminsavkoco@gmail.com',
                'password' => Hash::make('4dm1n5savko'),
                'name' => 'Admin Savko.Co',
                'phone_number' => '081234567890',
                'address' => 'Jl. Example No. 123, Banyuwangi',
                'role' => 'Owner'
            ],
            [
                'username' => 'prodstaff1',
                'email' => 'vaneca@gmail.com',
                'password' => Hash::make('12345679'),
                'name' => 'Vaneca',
                'phone_number' => '081298765432',
                'address' => 'Jl. Example No. 456, Banyuwangi',
                'role' => 'Production Staff'
            ],
            [
                'username' => 'prodstaff2',
                'email' => 'fadhlurrahman@gmail.com',
                'password' => Hash::make('12345679'),
                'name' => 'Fadhlurrahman',
                'phone_number' => '081212345678',
                'address' => 'Jl. Example No. 789, Banyuwangi',
                'role' => 'Production Staff'
            ],
            [
                'username' => 'diststaff1',
                'email' => 'nunniah@gmail.com',
                'password' => Hash::make('12345679'),
                'name' => 'Nunniah',
                'phone_number' => '081223344556',
                'address' => 'Jl. Example No. 321, Banyuwangi',
                'role' => 'Distribution Staff'
            ],
            [
                'username' => 'diststaff2',
                'email' => 'zahra@gmail.com',
                'password' => Hash::make('12345679'),
                'name' => 'Zahra',
                'phone_number' => '081234123412',
                'address' => 'Jl. Example No. 654, Banyuwangi',
                'role' => 'Distribution Staff'
            ]
        ]);
    }
}
