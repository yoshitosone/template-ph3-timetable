<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    protected $fillable = ['user_id', 'course_id'];

    protected function casts(): array
    {
        return ['decided_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // 画面に出す状態の名前
    public function statusLabel(): string
    {
        return ['pending' => '申請中', 'approved' => '承認', 'rejected' => '却下'][$this->status];
    }
}
