<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'exams';
    protected $fillable = [
        'teacher_id', 'subject_id', 'exam_name', 
        'total_question', 'duration', 'type_question', 
        'details', 'start_time', 'end_time', 'tokens'
    ];

    public function teacher()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_id', 'id');
    }

    public function subject()
    {
        return $this->belongsToMany(Subject::class, 'subject_id', 'id');
    }
}
