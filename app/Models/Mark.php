<?php

namespace App\Models;

use App\User;
use Eloquent;

class Mark extends Eloquent
{
    protected $fillable = ['student_id','subject_id','my_class_id','section_id','exam_id','ca_score','exam_score','cum','grade_id','acad_year_id'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function my_class()
    {
        return $this->belongsTo(GradeLevels::class,'my_class_id');
    }
    public function acad_year()
    {
        return $this->belongsTo(AcademicCalendar::class,'acad_year_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
