<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remarks extends Model
{
    use HasFactory;
    protected $fillable = ['remark','school_id','userType','grade_id'];

    public function grades(){
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
