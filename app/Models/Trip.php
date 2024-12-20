<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function children()
    {
        return $this->belongsToMany(Child::class, 'trip_child');
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
