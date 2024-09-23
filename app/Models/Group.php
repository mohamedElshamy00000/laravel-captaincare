<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // public function children()
    // {
    //     return $this->belongsToMany(Child::class, 'group_childrens', 'group_id', 'child_id');
    // }

    public function groupChildren()
    {
        return $this->hasMany(GroupChildren::class);
    }

    public function children()
    {
        return $this->belongsToMany(Child::class, 'group_childrens', 'group_id', 'child_id')->withPivot('status');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    // دالة لجلب المعلومات المتعلقة بالجروب
    public function getPlannedTripDetails()
    {
        return [
            'group_name' => $this->name,
            'description' => $this->description,
            'school' => $this->school->name,
            'driver' => $this->driver->name,
            'children' => $this->children->pluck('name'), // أسماء الأطفال
            'school_class' => $this->schoolClass->name,
            'waypoints' => $this->waypoints,
            'status' => $this->status,
        ];
    }

}
