<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
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
            subject::class,
            'course_subject',
            'course_id',
            'sub_id'
        );
    }
}
