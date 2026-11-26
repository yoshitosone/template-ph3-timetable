<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // courses は大学の授業データ。created_at・updated_at の列はない
    protected $table = 'courses';

    public $timestamps = false;

    public function entries()
    {
        return $this->hasMany(TimetableEntry::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // この授業のお知らせ
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    // この授業に付いたタグ（中間テーブル course_tag を通る）
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // この授業の受講が承認された学生。中間テーブルの名前が決まりどおり（course_user）ではないので指定する
    public function students()
    {
        return $this->belongsToMany(User::class, 'timetable_entries')->wherePivot('status', 'approved');
    }

    // この授業を「気になる」に入れている学生（中間テーブル favorites を通る）
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
