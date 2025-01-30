<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExercise extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'exercise_id', 'completed'];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
