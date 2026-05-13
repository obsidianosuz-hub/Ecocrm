<?php

namespace App\Traits;

trait BelongsToCompany
{
    public static function bootBelongsToCompany()
    {
        static::creating(function ($model) {
            if (auth()->check() && !$model->company_id) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    public function scopeForCompany($query)
    {
        if (auth()->check() && !auth()->user()->is_master) {
            return $query->where('company_id', auth()->user()->company_id);
        }
        return $query;
    }
}
