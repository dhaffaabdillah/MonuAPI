<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionMood extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'questions_mood';
    protected $fillable = ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e'];

}
