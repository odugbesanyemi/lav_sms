<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkingPeriods extends Model
{
    use HasFactory;
    protected $fillable=[
        'school_id',
        'acad_year_id',
        'mp_type',
        'title',
        'short_name',
        'parent_id',
        'start_date',
        'end_date',
        'post_start_date',
        'post_end_date',
        'does_grades',
        'does_exams',
        'does_comments',
    ];

    public function parent()
    {
        return $this->belongsTo(MarkingPeriods::class, 'parent_id');
    }
}
