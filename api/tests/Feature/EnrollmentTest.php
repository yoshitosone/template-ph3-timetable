<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// 学生が履修を申請する・取り消す。授業は複製したデータベースにあるものを使い、ユーザーと申請はテストの中で作る
class EnrollmentTest extends TestCase
{
    // テストが終わるたびに、テストの中で入れた行を巻き戻す
    use DatabaseTransactions;

    public function test_ログインしていないと申請できない(): void
    {
        $course = Course::where('code', 'CS101')->first();
        // 開発用から複製した申請も数に入るので、送る前と後の行数を比べる
        $before = TimetableEntry::count();

        $response = $this->post('/entries', ['course_id' => $course->id]);

        $response->assertRedirect('/login');
        $this->assertSame($before, TimetableEntry::count());
    }

    public function test_申請すると申請中の行が入る(): void
    {
        $student = User::factory()->create();
        $course = Course::where('code', 'CS101')->first();

        $response = $this->actingAs($student)->post('/entries', ['course_id' => $course->id]);

        $response->assertRedirect('/entries');
        $this->assertDatabaseHas('timetable_entries', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'pending',
        ]);
    }

    public function test_却下された授業には申請し直せない(): void
    {
        $student = User::factory()->create();
        $course = Course::where('code', 'CS101')->first();
        $entry = $student->entries()->create(['course_id' => $course->id]);
        $entry->status = 'rejected';
        $entry->save();

        $response = $this->actingAs($student)->from('/entries/create')->post('/entries', ['course_id' => $course->id]);

        $response->assertRedirect('/entries/create');
        $response->assertSessionHasErrors('course_id');
        $this->assertSame(1, TimetableEntry::where('user_id', $student->id)->count());
    }

    public function test_ほかの学生の申請は取り消せない(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $entry = $owner->entries()->create(['course_id' => Course::where('code', 'CS101')->first()->id]);

        $response = $this->actingAs($other)->delete("/entries/{$entry->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('timetable_entries', ['id' => $entry->id]);
    }

    public function test_自分の申請中の申請は取り消せる(): void
    {
        $student = User::factory()->create();
        $entry = $student->entries()->create(['course_id' => Course::where('code', 'CS101')->first()->id]);

        $response = $this->actingAs($student)->delete("/entries/{$entry->id}");

        $response->assertRedirect('/entries');
        $this->assertDatabaseMissing('timetable_entries', ['id' => $entry->id]);
    }

    public function test_承認された申請は取り消せない(): void
    {
        $student = User::factory()->create();
        $entry = $student->entries()->create(['course_id' => Course::where('code', 'CS101')->first()->id]);
        $entry->status = 'approved';
        $entry->save();

        $response = $this->actingAs($student)->from('/entries')->delete("/entries/{$entry->id}");

        $response->assertSessionHasErrors('entry');
        $this->assertDatabaseHas('timetable_entries', ['id' => $entry->id, 'status' => 'approved']);
    }
}
