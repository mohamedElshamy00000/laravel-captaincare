<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolClass;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::create([
            'name' => 'DIS School',
            'phone_number' => '1234567890',
            'email' => 'dis@school.com',
            'Latitude' => '31.4165',
            'Longitude' => '31.8133',
            'address' => '123 School St.',
        ]);

        SchoolClass::create([
            'name' => 'Class 1',
            'description' => 'Class 1',
            'check_out' => '12:00',
            'entry_time' => '07:00',
            'school_id' => $school->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
