<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'take_exam';
    protected $fillable = ['exam_id', 'user_id', 'question_list_id', 'answer_list', 'total_correct', 'scores', 'start_at', 'finished_at'];

    public function exams()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
