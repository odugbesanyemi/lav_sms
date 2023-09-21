<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPeriods extends Model
{
    protected $table = 'class_periods';
    protected $fillable = [
        'school_id',
        'title',
        'acad_year_id',
        'short_name',
        'start_time',
        'end_time',
        'length',
        'used_for_attendance',
    ];
    use HasFactory;
}
