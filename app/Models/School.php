<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $table = 'schools';
    protected $fillable = ['name','address','email','principal','phone','telephone','nationality','state','lga','logo','active','maintenance','generic_name'];

    public function students(){
        return $this->hasMany(StudentRecord::class);
    }
}

