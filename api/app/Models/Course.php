<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    // Beziehung: Ein Kurs hat viele Module
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    // Beziehung: Ein Kurs kann von mehreren Nutzern abgeschlossen werden
    public function certifications()
    {
        return $this->hasMany(UserCertification::class);
    }
}
