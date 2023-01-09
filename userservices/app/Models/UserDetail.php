<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'current_school',
        'previous_school',
        'exp',
        'father_name',
        'mother_name',
        'assigned_status',
        'assigned_to'
    ];

    public function teacherDetail(){
        return $this->hasOne(User::class,'id','assigned_to');
    }

    public function teacherSubject(){
        return $this->hasMany(TeacherSubject::class,'user_id','user_id');
    }
}
