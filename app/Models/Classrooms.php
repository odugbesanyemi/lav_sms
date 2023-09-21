<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classrooms extends Model
{
    protected $fillable = [
        'school_id',
        'acad_year_id',
        'title',
        'description',
        'capacity'
    ];
    use HasFactory;
}
