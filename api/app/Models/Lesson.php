<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'title', 'content'];

    // Beziehung: Eine Lektion gehört zu einem Modul
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Beziehung: Eine Lektion kann von mehreren Nutzern abgeschlossen werden
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }
}
