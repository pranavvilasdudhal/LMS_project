<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\User;


class UploadedPdf extends Model
{
    use HasFactory;

    protected $table = 'uploaded_pdfs';

    protected $fillable = [
        'user_id',
        'course_id',
        'subject_id',
        'section_id',
        'session_id',
        'pdf',
        'approved',
    ];

    // UploadedPdf.php

  public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
