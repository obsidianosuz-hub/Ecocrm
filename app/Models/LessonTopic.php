<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonTopic extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
