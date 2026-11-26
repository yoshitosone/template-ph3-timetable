<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    // 学生3人と、その人たちが書いたレビュー12件。W6 で授業の詳細に出す（最後の2件は W10 で使う）
    private const STUDENTS = [
        'student1@example.com' => '青木 さくら',
        'student2@example.com' => '内田 蓮',
        'student3@example.com' => '工藤 陽菜',
    ];

    // 授業の科目コード、書いた学生のメールアドレス、本文
    private const REVIEWS = [
        ['CS101', 'student1@example.com', '毎回の小さな課題で手を動かすので、初めてでもついていけました。'],
        ['CS101', 'student2@example.com', '演習の時間が長めで、わからないところをその場で聞けます。'],
        ['CS101', 'student3@example.com', '後半のペースが速いので、復習の時間を取っておくと安心です。'],
        ['DB210', 'student1@example.com', 'テーブルの設計を自分で考える回が一番おもしろかったです。'],
        ['DB210', 'student2@example.com', 'SQL を毎回書くので、PH2 の復習にもなりました。'],
        ['DB210', 'student3@example.com', '期末のレポートは早めに始めたほうがよいです。'],
        ['LA150', 'student2@example.com', '行列の計算は板書を写すだけでなく、手で解き直すと身につきます。'],
        ['LA150', 'student3@example.com', '小テストが毎週あるので、ためずに進められました。'],
        ['PG220', 'student1@example.com', '自分で作った画面を毎週見せ合うので、ほかの人の工夫が参考になります。'],
        ['ST201', 'student2@example.com', '例が身近なデータばかりで、数式が苦手でも追いかけやすかったです。'],
        // W10：改行を含むレビュー。画面でも改行して出したい
        ['SE240', 'student1@example.com', "グループで作る課題が2回あります。\n1回目は設計、2回目は実装です。\n早めにメンバーと役割を決めておくと楽です。"],
        // W10：HTML のタグを含むレビュー。タグは文字のまま出るのが正しい（HTML として出すと、取り消し線と太字になってしまう）
        ['UX250', 'student3@example.com', '<s>出席は取りません</s>と聞いていましたが、毎回取るようになりました。<b>遅刻3回で欠席1回</b>です。'],
    ];

    public function run(): void
    {
        foreach (self::STUDENTS as $email => $name) {
            User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => 'password123']);
        }

        foreach (self::REVIEWS as [$code, $email, $body]) {
            $user = User::where('email', $email)->firstOrFail();
            $course = Course::where('code', $code)->firstOrFail();
            // 2回実行しても、同じレビューは増やさない。user_id は $fillable にないので、ユーザーのレビューとして作る
            $user->reviews()->firstOrCreate(['course_id' => $course->id, 'body' => $body]);
        }
    }
}
