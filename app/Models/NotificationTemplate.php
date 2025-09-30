<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'status', 'content',
        'trigger_sensor', 'trigger_condition', 'trigger_value'
    ];
}


