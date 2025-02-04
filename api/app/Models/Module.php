<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'order'];

    // Beziehung: Ein Modul gehört zu einem Kurs
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Beziehung: Ein Modul hat viele Lektionen
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
