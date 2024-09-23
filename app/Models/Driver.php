<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Creatydev\Plans\Traits\HasPlans;
class Driver extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,HasPlans;

    // JWT methods
    protected $guard = 'driver';
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $guarded = [];
    
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
    
    // جلب الرحلات الخاصة بالكابتن
    public function getPlannedTrips()
    {
        return $this->groups()->with('children', 'school', 'schoolClass')->get()->map(function ($group) {
            return [
                'group_name' => $group->name,
                'children' => $group->children->pluck('name'),
                'school' => $group->school->name,
                'school_class' => $group->schoolClass->name,
                'status' => $group->status,
                'trip_type' => 'Morning', // صباحاً
                'waypoints' => $group->waypoints,
                'trip_date' => now()->toDateString(),
            ];
        });
    }
}
