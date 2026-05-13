<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftPause extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $guarded = [];
    
    protected $casts = [
        'paused_at' => 'datetime',
        'resumed_at' => 'datetime',
    ];
}
