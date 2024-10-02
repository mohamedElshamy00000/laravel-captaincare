<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Driver::create([
            'name' => 'Jane Smith',
            'phone' => '01010101010',
            'photo' => 'photo.jpg',
            'license' => '1234567890',
            'status' => '1',
            'email' => 'driver@example.com',
            'address' => 'new Dmitta',
            'Latitude' => '31.4165',
            'Longitude' => '31.8133',
            'password' => bcrypt('password'),
        ]);
    }
}
