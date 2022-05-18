<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamPackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'exam_package';
    protected $fillable = ['exam_id', 'question_id'];
    protected $guarded = array();
    public static $rules = array();

    public $relationships = array('exams', 'questions');
    public function exams()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function questions()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
