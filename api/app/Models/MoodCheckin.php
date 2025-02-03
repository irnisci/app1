<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodCheckin extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'mood', 'note', 'checked_at'];

    protected $casts = [
        'checked_at' => 'datetime',
    ];
}
