<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappNotificationSetting extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_notification_settings';

    protected $fillable = [ 'audience_type', 'audience_ids', 'template_id' ];

    protected $casts = [ 'audience_ids' => 'array' ];
}


