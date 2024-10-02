<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Creatydev\Plans\Traits\HasPlans;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\Father\SubscriptionTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Father extends Authenticatable implements JWTSubject
{

    use HasFactory, HasRoles, HasApiTokens, Notifiable,HasPlans;
    use SubscriptionTrait;

    protected $guarded = [];

    protected $guard = 'father';
    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function children()
    {
        return $this->belongsToMany(Child::class, 'father_children');
    }

    // public function invoices()
    // {
    //     return $this->hasMany(SubscriptionInvoice::class, 'user_id');
    // }
    public function invoices()
    {
        return $this->hasMany(SubscriptionInvoice::class);
    }

    public function subscription(){
        return $this->hasOne(SubscriptionInvoice::class,'user_id')
                ->where('due_date', '>', now()->format('Y-m-d'));
    }

    // جلب الرحلات المخطط لها لأطفال ولي الأمر
    public function getPlannedTrips()
    {
        return $this->children()->with('group.school', 'group.driver', 'group.schoolClass')->get()->map(function ($child) {
            return [
                'child_name' => $child->name,
                'group_name' => $child->group->name,
                'school' => $child->group->school->name,
                'driver' => $child->group->driver->name,
                'status' => $child->group->status,
                'trip_type' => 'Morning', // صباحاً
                'waypoints' => $child->group->waypoints,
                'trip_date' => now()->toDateString(),
            ];
        });
    }
}
