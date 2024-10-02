<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fathers()
    {
        return $this->belongsToMany(Father::class, 'father_children');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function classe()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    // public function groups()
    // {
    //     return $this->belongsToMany(Group::class, 'group_child');
    // }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_children')->withPivot('status');
    }

    public function groupChildren()
    {
        return $this->hasMany(GroupChildren::class);
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'trip_child');
    }
}
