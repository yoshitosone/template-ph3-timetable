<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timetable_entries', function (Blueprint $table) {
            // 曜日・時限・授業名・教室は courses から引くので、この表からは消す
            $table->dropUnique(['day', 'period']);
            $table->dropColumn(['day', 'period', 'title', 'room']);
            // どの授業を取ったか。courses.id を指し、同じ授業は1回しか登録できない
            $table->foreignId('course_id')->unique()->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('timetable_entries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_id');
            $table->unsignedTinyInteger('day');
            $table->unsignedTinyInteger('period');
            $table->string('title');
            $table->string('room')->nullable();
            $table->unique(['day', 'period']);
        });
    }
};
