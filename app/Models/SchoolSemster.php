<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSemster extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
