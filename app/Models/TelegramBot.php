<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBot extends Model
{
    use HasFactory, \App\Traits\BelongsToCompany;

    protected $guarded = [];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
