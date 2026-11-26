<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    public function index()
    {
        // 区画1・区画2 の SQL（sql/w01.sql と同じもの）
        $monday = DB::select('SELECT period, name, teacher, room FROM courses WHERE day = ? ORDER BY period, code', [1]);
        $required = DB::select('SELECT name, credits FROM courses WHERE required = TRUE ORDER BY code');

        return view('timetable', ['monday' => $monday, 'required' => $required]);
    }
}
