<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_name',
        'course_image',
        'course_descripion',
        'mrp',
        'sell_price',
    ];

    public function subject()
    {
        return $this->belongsToMany(
            Subject::class,
            'course_subject',
            'course_id',
            'sub_id'
        );
    }

    // ✅ ADD THIS (VERY IMPORTANT)
    public function sessions()
    {
        return $this->hasManyThrough(
            Session::class,
            Section::class,
            'course_id',     // sections.course_id
            'section_id',    // sessions.section_id
            'course_id',
            'section_id'
        );
    }

    // ✅ ADD THIS
    public function progress()
    {
        return $this->hasMany(Progress::class, 'course_id', 'course_id');
    }
}
