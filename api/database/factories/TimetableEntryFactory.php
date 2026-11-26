<?php

namespace Database\Factories;

use App\Models\TimetableEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

// W11：テストで使う履修の申請を作る。だれの・どの授業の申請かは、TimetableEntryFactory::new()->create(['user_id' => ..., 'course_id' => ...]) で決める
/**
 * @extends Factory<TimetableEntry>
 */
class TimetableEntryFactory extends Factory
{
    protected $model = TimetableEntry::class;

    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'course_id' => CourseFactory::new(),
            'status' => 'pending',
        ];
    }

    // 承認が決まった申請
    public function approved(): static
    {
        return $this->state(fn () => ['status' => 'approved', 'decided_at' => now()]);
    }

    // 却下が決まった申請
    public function rejected(): static
    {
        return $this->state(fn () => ['status' => 'rejected', 'decided_at' => now()]);
    }
}
