<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id', 'completed_at'];

    // Beziehung: Ein Zertifikat gehört zu einem Nutzer
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Beziehung: Ein Zertifikat gehört zu einem Kurs
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
