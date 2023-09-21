<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource_types extends Model
{
    protected $table = 'resource_type';
    protected $fillable = ['name'];

    use HasFactory;
    public function students(){
        return $this->hasMany(Resource::class);
    }
}
