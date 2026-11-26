<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 履修を承認する管理者を1人だけ作る。画面からは管理者になれない
        // is_admin は $fillable に入れていない。db:seed の中では一括代入の保護が外れるので、ここでは保存できる
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => '教務課', 'password' => 'password123', 'is_admin' => true]
        );

        // ゼミは少人数の授業なので、定員を3人にする
        Course::where('code', 'SE300')->update(['capacity' => 3]);
    }
}
