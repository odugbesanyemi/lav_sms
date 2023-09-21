<?php

namespace App\Models;

use Eloquent;

class Exam extends Eloquent
{
    protected $fillable = ['school_id','name', 'marking_period_id', 'acad_year_id'];

    public function marking_period()
    {
        return $this->belongsTo(MarkingPeriods::class,'marking_period_id');
    }
    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }
    public function acad_year()
    {
        return $this->belongsTo(AcademicCalendar::class,'acad_year_id');
    }
}
