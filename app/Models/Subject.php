<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'subjects';
    protected $fillable = [
        'subject_name', 'details'    
    ];
    
    public $timestamps = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function teacherSubject()
    {
        return $this->belongsToMany(TeacherSubject::class, 'subject_id', 'id');
    }
}
