<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // このユーザーの時間割の行（1人に、時間割の行がたくさん）
    public function entries()
    {
        return $this->hasMany(TimetableEntry::class);
    }

    // このユーザーが書いたレビュー
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // このユーザーが申請している授業（中間テーブル timetable_entries を通る。申請中・却下も含む）
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'timetable_entries');
    }

    // このユーザーが「気になる」に入れている授業。同じ users と courses を、こちらは favorites でつなぐ
    public function favoriteCourses()
    {
        return $this->belongsToMany(Course::class, 'favorites');
    }
}
