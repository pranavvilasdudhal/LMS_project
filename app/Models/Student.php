<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'student_email',
        'student_phone',
        'student_address',
        'student_password',
    ];

    protected $primaryKey = 'student_id';

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'student_email','user_id');
    }
}
