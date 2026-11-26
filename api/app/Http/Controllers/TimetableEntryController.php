<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TimetableEntryController extends Controller
{
    public function index(Request $request)
    {
        // ログイン中のユーザーの行だけを、曜日・時限の順に並べる。授業ごとの受講者数も、まとめて数える
        $entries = $request->user()->entries()
            ->with(['course' => function ($query) {
                $query->withCount('students');
            }])
            ->join('courses', 'courses.id', '=', 'timetable_entries.course_id')
            ->orderBy('courses.day')
            ->orderBy('courses.period')
            ->select('timetable_entries.*')
            ->get();

        // 履修が承認された授業の、この7日間のお知らせ（申請中・却下の授業の分は出さない）
        $courseIds = $entries->where('status', 'approved')->pluck('course_id');
        $announcements = Announcement::with('course')
            ->whereIn('course_id', $courseIds)
            ->where('created_at', '>=', now()->subDays(7))
            ->latest('id')
            ->get();

        return view('entries.index', ['entries' => $entries, 'announcements' => $announcements]);
    }

    public function create()
    {
        $courses = Course::orderBy('day')->orderBy('period')->orderBy('id')->get();

        return view('entries.create', ['courses' => $courses]);
    }

    public function store(Request $request)
    {
        $request->validate(
            ['course_id' => 'required|exists:courses,id'],
            ['course_id.*' => '授業を一覧から選んでください']
        );

        // 却下された授業には申請し直せない。却下の行は残すので、同じ授業の行をもう1本入れると UNIQUE 制約で止まってしまう
        $course = Course::find($request->input('course_id'));
        if ($request->user()->entries()->where('course_id', $course->id)->where('status', 'rejected')->exists()) {
            return back()->withErrors(['course_id' => '却下された授業には申請し直せません'])->withInput();
        }

        // 同じ曜日・時限の確認は、自分の時間割の中だけで行う（ほかの学生が取っていても関係ない）。却下された申請は数えない
        $taken = $request->user()->entries()->where('status', '!=', 'rejected')->whereHas('course', function ($query) use ($course) {
            $query->where('day', $course->day)->where('period', $course->period);
        })->exists();
        if ($taken) {
            return back()->withErrors(['course_id' => 'その曜日・時限にはすでに授業が登録されています'])->withInput();
        }

        // user_id はフォームから受け取らず、ログイン中のユーザーの id が入る。status は列の既定値の pending（申請中）になる
        $request->user()->entries()->create(['course_id' => $course->id]);

        return redirect('/entries');
    }

    public function destroy($id)
    {
        $entry = TimetableEntry::findOrFail($id);
        // 持ち主でなければ 403 で止める（TimetableEntryPolicy の delete）
        Gate::authorize('delete', $entry);
        // 取り消せるのは、まだ決まっていない申請だけ。承認や却下の記録は消さない。
        // 確かめてから消すまでのあいだに管理者が承認することもあるので、確かめるのと消すのを1つの SQL で行う
        $deleted = TimetableEntry::whereKey($entry->id)->where('status', 'pending')->delete();
        if ($deleted === 0) {
            return back()->withErrors(['entry' => '承認や却下が決まった申請は取り消せません']);
        }

        return redirect('/entries');
    }
}
