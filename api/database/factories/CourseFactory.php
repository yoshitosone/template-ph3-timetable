<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

// W11：テストで使う授業を作る。CourseFactory::new()->create([...]) のように使い、テストに関係する列だけを上書きする
// Course モデルには HasFactory を付けていないので、Course::factory() ではなく CourseFactory::new() と書く
/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            // 授業コードの決まり（英大文字2〜3文字と数字3つ）に合い、初期データの授業とは重ならないコード
            'code' => 'TS' . fake()->unique()->numberBetween(100, 999),
            'name' => 'テスト用の授業',
            'teacher' => fake()->name(),
            // 初期データには6限の授業がないので、上書きしなければ初期データの授業とは重ならない。
            // ファクトリで作った授業どうしは重なることがある。重なりが結果に関わるテストでは、教室・曜日・時限を自分で渡す
            // （J302 は初期データのどの授業も使っていない教室で、ここでは選ばない。テストで空いている教室として使える）
            'day' => fake()->numberBetween(1, 5),
            'period' => 6,
            'room' => fake()->randomElement(['A101', 'B102', 'C201']),
            'credits' => 2,
            'required' => false,
        ];
    }
}
