<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            PermissionsTableSeeder::class,
            SchoolSeeder::class,
            FatherAndChildrenSeeder::class,
            DriverSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
