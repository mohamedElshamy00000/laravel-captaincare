<?php

namespace App\Traits\Father;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Father;
use App\Models\AgentClient;
use App\Models\ProjectInvoice;
use Illuminate\Support\Facades\Auth;

trait SubscriptionTrait
{
    public function hasFeature($permissionCode) {
        $user = Auth::user();
        if ($user->hasActiveSubscription()) {
            $feature = $user->activeSubscription()->features()->code($permissionCode)->first();
            if ($feature != null) {
                return $feature;
            } else {
                return false;
            }    
        }
        return false;
    }

    public function CountClientsWithinSubscription() {

        $user = Auth::user();
        // return $userId;
        return $user
        ->friends()
        ->wherePivot('created_at', '>=', Carbon::now()->subDays($user->activeSubscription()->plan->duration))
        ->count();

    }
    public function CreditWithinSubscription() {

        $user = Auth::user();
        // return $user->name;
        return ProjectInvoice::where('user_id',$user->id)
        ->where('invoice_type', '=', 2)
        ->where('created_at', '>=', Carbon::now()->subDays($user->activeSubscription()->plan->duration))
        ->sum('amount');

    }
}