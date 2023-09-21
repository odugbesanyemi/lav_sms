<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkPreferences extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id',
        'acad_year_id',
        'marking_period_id',
        'ca_final_score',
        'exam_final_score',
        'show_skills',
        'type_order'
    ];
}
