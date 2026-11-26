<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    // W9 でログインに使う学生3人。W6 の ReviewSeeder の学生と同じ人（2回実行しても増えない）。W12 で4人目を足した
    // パスワードはどの人も password123（User モデルが hashed で保存する）
    private const STUDENTS = [
        'student1@example.com' => '青木 さくら',
        'student2@example.com' => '内田 蓮',
        'student3@example.com' => '工藤 陽菜',
    ];

    // W12：レビューを書ける学生。受講が承認された授業を2つ持っている（申請と承認を画面で操作しなくても、すぐに試せる）
    private const REVIEWER = ['student4@example.com', '佐藤 湊'];
    private const APPROVED = ['CS101', 'LA150'];

    public function run(): void
    {
        foreach (self::STUDENTS as $email => $name) {
            User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => 'password123']);
        }

        [$email, $name] = self::REVIEWER;
        $reviewer = User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => 'password123']);
        foreach (self::APPROVED as $code) {
            $course = Course::where('code', $code)->firstOrFail();
            // status と decided_at は $fillable にない（学生が送った値で承認にさせない）ので、forceFill で入れる
            $entry = TimetableEntry::firstOrNew(['user_id' => $reviewer->id, 'course_id' => $course->id]);
            $entry->forceFill(['status' => 'approved', 'decided_at' => $entry->decided_at ?? now()])->save();
        }
    }
}
