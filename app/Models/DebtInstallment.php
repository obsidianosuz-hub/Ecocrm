<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtInstallment extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $guarded = [];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
