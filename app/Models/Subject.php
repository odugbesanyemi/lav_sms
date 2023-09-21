<?php

namespace App\Models;

use App\User;
use Eloquent;

class Subject extends Eloquent
{
    protected $fillable = ['name', 'school_id', 'my_class_id', 'teacher_id', 'slug'];

    public function my_class()
    {
        return $this->belongsTo(GradeLevels::class,'my_class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
