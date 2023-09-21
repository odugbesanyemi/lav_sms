<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevels extends Model
{
    protected $table="grade_levels";
    protected $fillable =
    [
        'school_id',
        'acad_year_id',
        'title',
        'short_name',
        'next_grade_id'
    ];
    use HasFactory;
    public function section()
    {
        return $this->hasMany(Section::class,'my_class_id');
    }
    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }
    public function student_record()
    {
        return $this->hasMany(StudentRecord::class);
    }

}
