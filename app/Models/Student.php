<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function payments()
    {
        return $this->hasMany(AcademyPayment::class);
    }
}
