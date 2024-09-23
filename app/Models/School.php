<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Define relationships with other models (optional)

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function semesters()
    {
        return $this->hasMany(SchoolSemster::class);
    }
    function holidays() {
        return $this->hasMany(SchoolHoliday::class);
    }
}
