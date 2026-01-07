<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = "cart_items";

    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_id',
        'quantity'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
