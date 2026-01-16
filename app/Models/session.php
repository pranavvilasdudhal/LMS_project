<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
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
        'unlocked', // THIS WAS MISSING
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

    //  Relation: Session → Progress
    public function progress()
    {
        return $this->hasOne(SessionProgress::class, 'session_id');
    }
}
