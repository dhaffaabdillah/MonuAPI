<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'questions_cognitive';
    protected $primaryKey = 'id';
    protected $fillable = [
        'teacher_id', 'subject_id', 'file_type', 'file', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'correct_answer', 'total_correct', 'total_wrong'
    ];
    public $timestamps = ['created_at', 'updated_at', 'deleted_at'];

    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function exam()
    {
        return $this->hasMany(ExamPackage::class, 'question_id', 'id');
    }
}
