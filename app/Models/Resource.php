<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resource';
    protected $fillable = ['title','resource_type_id','description','subject_id','added_by','school_id','acad_year_id','filename','image'];

    use HasFactory;
    public function students(){
        return $this->belongsTo(Resource_types::class,'resource_type_id');
    }
}
