<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // course_id はフォームから受け取らず、$course->announcements()->create() で入れる
    protected $fillable = ['title', 'body'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
