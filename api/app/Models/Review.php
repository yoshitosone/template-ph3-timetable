<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // user_id は入れない。だれが書いたかは、いつもログイン中のユーザーから決める
    protected $fillable = ['course_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
