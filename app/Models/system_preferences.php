<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class system_preferences extends Model
{
    use HasFactory;
    protected $table = 'system_preferences';
    protected $fillable = [
        'school_id',
        'maintenance_status',
        'maintenance_message',
        'allow_email',
        'notify_email',
        'half_day_minutes',
        'full_day_minutes'        
    ];
}
