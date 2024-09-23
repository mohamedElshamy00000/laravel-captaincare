<?php

namespace App\Models;

use Creatydev\Plans\Models\PlanModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function plan(){
        return $this->belongsTo(PlanModel::class,'plan_id');
    }
    public function users(){
        return $this->belongsTo(Father::class, 'user_id');
    }
    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

}
