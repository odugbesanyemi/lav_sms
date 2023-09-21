<?php

namespace App\Models;

use Eloquent;

class Payment extends Eloquent
{
    protected $fillable = ['title', 'amount', 'my_class_id', 'description', 'year', 'ref_no','school_id','acad_year_id','term_id'];

    public function my_class()
    {
        return $this->belongsTo(GradeLevels::class,'my_class_id');
    }

    public function term()
    {
        return $this->belongsTo(MarkingPeriods::class,'term_id');
    }
}
