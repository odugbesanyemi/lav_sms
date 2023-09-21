<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvents extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'school_id',
        'acad_year_id',
        'title',
        'description',
        'start_date',
        'end_date'
    ];
}
