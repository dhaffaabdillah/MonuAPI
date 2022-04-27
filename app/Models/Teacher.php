<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'teachers';
    protected $fillable = [
        'user_id', 'teacher_name'
    ];
    protected $primaryKey = 'id';   

    public $timestamps = ['created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function teacherSubject()
    {
        return $this->belongsToMany(TeacherSubject::class, 'teacher_id', 'id');
    }
}
