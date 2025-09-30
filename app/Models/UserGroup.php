<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'description', 'status' ];

    // Existing user_groups table may not have created_at/updated_at
    public $timestamps = false;
}


