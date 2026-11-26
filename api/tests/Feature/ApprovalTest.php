<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// 管理者が申請を承認する。管理者だけ・申請中だけ・定員まで、の3つを確かめる
class ApprovalTest extends TestCase
{
    use DatabaseTransactions;

    // 定員を決めた授業に、承認済みの学生を $approved 人入れて返す
    private function courseWith(int $capacity, int $approved): Course
    {
        $course = Course::where('code', 'CS101')->first();
        $course->capacity = $capacity;
        $course->save();
        // 開発用から複製した申請が残っていると人数がずれるので、先に消しておく（テストが終われば戻る）
        $course->entries()->delete();
        for ($i = 0; $i < $approved; $i++) {
            $this->entryOf(User::factory()->create(), $course, 'approved');
        }

        return $course;
    }

    private function entryOf(User $student, Course $course, string $status = 'pending'): TimetableEntry
    {
        $entry = $student->entries()->create(['course_id' => $course->id]);
        $entry->status = $status;
        $entry->save();

        return $entry;
    }

    public function test_学生は承認の画面を開けない(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)->get('/admin/entries')->assertForbidden();
    }

    public function test_学生は承認できない(): void
    {
        $entry = $this->entryOf(User::factory()->create(), $this->courseWith(3, 0));

        $this->actingAs(User::factory()->create())->post("/admin/entries/{$entry->id}/approve")->assertForbidden();
        $this->assertSame('pending', $entry->fresh()->status);
    }

    public function test_管理者が承認すると承認済みになる(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $entry = $this->entryOf(User::factory()->create(), $this->courseWith(3, 0));

        $response = $this->actingAs($admin)->post("/admin/entries/{$entry->id}/approve");

        $response->assertRedirect('/admin/entries');
        $entry->refresh();
        $this->assertSame('approved', $entry->status);
        $this->assertNotNull($entry->decided_at);
    }

    public function test_定員まであと1人なら承認できる(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $entry = $this->entryOf(User::factory()->create(), $this->courseWith(3, 2));

        $this->actingAs($admin)->post("/admin/entries/{$entry->id}/approve");

        $this->assertSame('approved', $entry->fresh()->status);
    }

    public function test_定員に達した授業は承認できない(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $entry = $this->entryOf(User::factory()->create(), $this->courseWith(3, 3));

        $response = $this->actingAs($admin)->from('/admin/entries')->post("/admin/entries/{$entry->id}/approve");

        $response->assertSessionHasErrors('entry');
        $this->assertSame('pending', $entry->fresh()->status);
    }

    public function test_却下した申請はあとから承認できない(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $entry = $this->entryOf(User::factory()->create(), $this->courseWith(3, 0), 'rejected');

        $this->actingAs($admin)->from('/admin/entries')->post("/admin/entries/{$entry->id}/approve");

        $this->assertSame('rejected', $entry->fresh()->status);
    }
}
