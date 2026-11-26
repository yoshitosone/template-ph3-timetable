<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Tag;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q', '');
        $tag = $request->filled('tag') ? Tag::findOrFail($request->query('tag')) : null;

        // タグは授業ごとに取りに行かず、まとめて1回で取る。「気になる」の人数と承認済みの人数も一緒に数える
        $query = Course::with('tags')->withCount(['favoritedBy', 'students']);

        // 授業名か担当教員の部分一致。打たれた文字は SQL の文には入れず、値として別に渡す
        if ($q !== '') {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")->orWhere('teacher', 'like', "%{$q}%");
            });
        }

        // 選んだタグが付いている授業だけにする
        if ($tag) {
            $query->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            });
        }

        $courses = $query->orderBy('day')->orderBy('period')->orderBy('id')->get();
        // ログイン中のユーザーの申請の状態を、授業の id ごとに引けるようにする（[授業の id => 'pending'] の形）
        $myStatuses = $request->user()->entries()->pluck('status', 'course_id');
        // ログイン中のユーザーが「気になる」に入れている授業の id
        $myFavoriteIds = $request->user()->favoriteCourses()->pluck('courses.id');

        return view('courses.index', ['courses' => $courses, 'q' => $q, 'tag' => $tag, 'myStatuses' => $myStatuses, 'myFavoriteIds' => $myFavoriteIds]);
    }

    public function show(Course $course)
    {
        $course->load(['tags', 'students']);
        $reviews = $course->reviews()->with('user')->latest('id')->get();
        // この授業のお知らせだけを、新しい順に
        $announcements = $course->announcements()->latest('id')->get();

        return view('courses.show', ['course' => $course, 'reviews' => $reviews, 'announcements' => $announcements]);
    }
}
