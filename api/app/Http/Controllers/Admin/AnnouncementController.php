<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AnnouncementMail;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AnnouncementController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $request->validate(
            ['title' => 'required|string|max:50', 'body' => 'required|string|max:1000'],
            [
                'title.required' => 'タイトルを入力してください',
                'title.max' => 'タイトルは50文字以内で入力してください',
                'body.required' => '本文を入力してください',
                'body.max' => '本文は1000文字以内で入力してください',
            ]
        );

        // 先に保存してから送る。送る途中で止まっても、お知らせは授業のページに残る
        $announcement = $course->announcements()->create($request->only(['title', 'body']));

        // 届けるのは、この授業の履修が承認された学生だけ（申請中・却下の学生には送らない）
        foreach ($course->students as $student) {
            Mail::to($student)->send(new AnnouncementMail($announcement, $student));
        }

        return redirect("/courses/{$course->id}");
    }
}
