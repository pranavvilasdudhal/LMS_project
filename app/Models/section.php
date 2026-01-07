<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

     use HasFactory;

    protected $table = 'section';
    protected $primaryKey = 'section_id';
    protected $fillable = [
        'sec_title',
        'sec_description',
        'subject_id'
    ];

    //  Relation: Section belongs to a Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

     //  Relationship with Session
    public function sessions()
    {
        return $this->hasMany(Session::class, 'section_id', 'section_id');
    }
}
