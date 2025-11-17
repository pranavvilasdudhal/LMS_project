<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subject extends Model
{
 use HasFactory;

    protected $fillable = [
        'subject_name',
        'subject_description',
        'subject_image'
    ];

    protected $primaryKey = 'subject_id';
    protected $table = 'subject';

    
    public function sections()
    {
        return $this->hasMany(Section::class, 'subject_id', 'subject_id');
    }

    
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_subject',
            'sub_id',
            'course_id'
        );
    }
}
