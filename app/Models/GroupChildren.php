<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChildren extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'child_id',
        'status',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function student()
    {
        return $this->belongsTo(Child::class);
    }
}
