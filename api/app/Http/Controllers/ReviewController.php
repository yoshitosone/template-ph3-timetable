<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate(
            ['body' => 'required|max:1000'],
            [
                'body.required' => 'レビューを入力してください',
                'body.max' => 'レビューは1000文字以内で入力してください',
            ]
        );

        // 書いた人はフォームから受け取らず、ログイン中のユーザーにする
        $request->user()->reviews()->create([
            'course_id' => $course->id,
            'body' => $validated['body'],
        ]);

        return redirect("/courses/{$course->id}");
    }

    public function destroy(Review $review)
    {
        // 書いた本人でなければ 403 で止める（ReviewPolicy の delete）
        Gate::authorize('delete', $review);
        $review->delete();

        return redirect("/courses/{$review->course_id}");
    }
}
