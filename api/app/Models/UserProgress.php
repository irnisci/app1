<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lesson_id', 'completed_at'];

    // Beziehung: Fortschritt gehört zu einem Nutzer
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Beziehung: Fortschritt gehört zu einer Lektion
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
