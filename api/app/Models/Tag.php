<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    // このタグが付いた授業（中間テーブル course_tag を通る）
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
