<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TimetableEntry;
use Illuminate\Support\Facades\DB;

class EntryApprovalController extends Controller
{
    public function index()
    {
        // 申請中の行を古い順に。授業ごとの承認済みの人数も一緒に数える
        $pending = TimetableEntry::where('status', 'pending')
            ->with(['user', 'course' => function ($query) {
                $query->withCount('students');
            }])
            ->orderBy('id')
            ->get();
        // 承認・却下が決まった行を、新しい順に10件
        $decided = TimetableEntry::where('status', '!=', 'pending')
            ->with(['user', 'course'])
            ->latest('decided_at')
            ->limit(10)
            ->get();

        return view('admin.entries', ['pending' => $pending, 'decided' => $decided]);
    }

    public function approve(TimetableEntry $entry)
    {
        // 数える・確かめる・書き換えるを、1つのトランザクションの中で行う
        return DB::transaction(function () use ($entry) {
            // 授業の行をロックする。同じ授業を承認しようとするほかのリクエストは、このトランザクションが終わるまで待つ
            $course = Course::lockForUpdate()->findOrFail($entry->course_id);
            // 待っているあいだに状態が変わっているかもしれないので、ロックしてから読み直す
            $entry = TimetableEntry::lockForUpdate()->findOrFail($entry->id);

            if ($entry->status !== 'pending') {
                return back()->withErrors(['entry' => '申請中ではないので承認できません']);
            }
            if ($course->students()->count() >= $course->capacity) {
                return back()->withErrors(['entry' => "{$course->name}は定員（{$course->capacity}人）に達しています"]);
            }

            // status は fillable に入れていない。フォームから送られた値で書き換わらないよう、1つずつ代入する
            $entry->status = 'approved';
            $entry->decided_at = now();
            $entry->save();

            return redirect('/admin/entries');
        });
    }

    public function reject(TimetableEntry $entry)
    {
        return DB::transaction(function () use ($entry) {
            $entry = TimetableEntry::lockForUpdate()->findOrFail($entry->id);

            if ($entry->status !== 'pending') {
                return back()->withErrors(['entry' => '申請中ではないので却下できません']);
            }

            $entry->status = 'rejected';
            $entry->decided_at = now();
            $entry->save();

            return redirect('/admin/entries');
        });
    }
}
