<?php

namespace App\Models;

use Eloquent;

class Dorm extends Eloquent
{
    protected $fillable = ['name', 'description','school_id'];
}
