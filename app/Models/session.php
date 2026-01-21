<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';

    protected $fillable = [
        'titel',
        'type',
        'video',
        'pdf',
        'task',
        'exam',
        'section_id',
        'unlocked',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

    // ✅ FIX THIS
    public function progress()
    {
        return $this->hasMany(Progress::class, 'session_id', 'id');
    }
}
