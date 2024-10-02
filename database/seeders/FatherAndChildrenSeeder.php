<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Father;
use App\Models\Child;
use App\Models\School;
use App\Models\SchoolClass;

class FatherAndChildrenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $father = Father::create([
            'name' => 'Mohamed',
            'phone' => '01010101010',
            'status' => '1',
            'state' => 'Cairo',
            'city' => 'Cairo',
            'latitude' => '31.4165',
            'longitude' => '31.8133',
            'email' => 'mohamed@example.com',
            'password' => bcrypt('password'),
        ]);

        $children = [
            [
                'name' => 'Child One',
                'age' => 10,
                'photo' => 'https://via.placeholder.com/150',
                'phone' => '01010101010',
                'address' => '123 Street',
                'status' => '1',
                'school_id' => School::first()->id,
                'school_class_id' => SchoolClass::first()->id,
                'Latitude' => '31.4165',
                'Longitude' => '31.8133',
            ],
            [
                'name' => 'Child Two',
                'photo' => 'https://via.placeholder.com/150',
                'age' => 10,
                'phone' => '01010101010',
                'address' => '123 Street',
                'status' => '1',
                'school_id' => School::first()->id,
                'school_class_id' => SchoolClass::first()->id,
                'Latitude' => '31.4165',
                'Longitude' => '31.8133',
            ]
        ];

        foreach ($children as $child) {
            Child::create($child);
        }

        // $father->children()->attach($children);
        $father->children()->createMany($children);
    }
}
