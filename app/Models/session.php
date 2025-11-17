<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class session extends Model
{
   use HasFactory;

    protected $table = 'session'; 

    protected $fillable = [
        'titel',
        'type',
        'video',
        'pdf',
        'task',
        'exam',
        'section_id',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

}
