<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
