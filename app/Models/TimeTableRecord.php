<?php

namespace App\Models;

use Eloquent;

class TimeTableRecord extends Eloquent
{
    protected $fillable = ['name', 'my_class_id', 'exam_id', 'acad_year_id','school_id','marking_period_id'];

    public function my_class()
    {
        return $this->belongsTo(GradeLevels::class,'my_class_id');
    }

    public function marking_period()
    {
        return $this->belongsTo(MarkingPeriods::class);
    }
    public function acad_year()
    {
        return $this->belongsTo(AcademicCalendar::class,'acad_year_id');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
