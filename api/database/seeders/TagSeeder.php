<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    // タグの名前と、そのタグを付ける授業の科目コード
    private const TAGS = [
        '演習あり' => ['CS101', 'PG220', 'DS230', 'PG120', 'UX250', 'BZ120'],
        '少人数' => ['EN101', 'EN102', 'CN100', 'UX250', 'SE300'],
        '英語で実施' => ['EN101', 'EN102'],
        'レポート評価' => ['IE110', 'SO120', 'HI130', 'LI140', 'CA200', 'ART100'],
        '1年生向け' => ['CS101', 'EC100', 'IE110', 'EN101', 'PS100', 'LW110'],
    ];

    public function run(): void
    {
        foreach (self::TAGS as $name => $codes) {
            $tag = Tag::firstOrCreate(['name' => $name]);
            $ids = Course::whereIn('code', $codes)->pluck('id');
            // 2回実行しても、すでに付いているタグは付け直さない
            $tag->courses()->syncWithoutDetaching($ids);
        }
    }
}
