<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timetable_entries', function (Blueprint $table) {
            // だれの時間割の行か。users.id を指し、ユーザーを消したらその人の行も消える
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // 同じ授業を取れるのは「1人1回」。ほかの学生と同じ授業は取れる
            $table->dropUnique(['course_id']);
            $table->unique(['user_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::table('timetable_entries', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'course_id']);
            $table->dropConstrainedForeignId('user_id');
            $table->unique('course_id');
        });
    }
};
