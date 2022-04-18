<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherSubject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teacher_subjects';
    protected $fillable = [
        'teacher_id', 'subject_id'
    ];

    public $timestamps = true;

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'subject_id', 'id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'teacher_id', 'id');
    }
    
}
