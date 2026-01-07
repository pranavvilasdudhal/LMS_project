<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionProgress extends Model
{
    protected $table = 'session_progress';

    protected $fillable = [
        'user_id',
        'session_id',
        'unlocked',
        'pdf_approved',
        'task_completed',
    ];
}
