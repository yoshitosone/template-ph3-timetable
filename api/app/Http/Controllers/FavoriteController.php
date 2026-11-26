<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request, Course $course)
    {
        // すでに入れてあれば何もしない（2回押しても行は1つのまま）
        $request->user()->favoriteCourses()->syncWithoutDetaching([$course->id]);

        return back();
    }

    public function destroy(Request $request, Course $course)
    {
        $request->user()->favoriteCourses()->detach($course->id);

        return back();
    }
}
