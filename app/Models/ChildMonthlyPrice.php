<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildMonthlyPrice extends Model
{
    use HasFactory;

    protected $fillable = ['child_id', 'price'];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
