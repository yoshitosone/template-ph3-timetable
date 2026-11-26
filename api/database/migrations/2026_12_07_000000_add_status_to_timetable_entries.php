<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timetable_entries', function (Blueprint $table) {
            // pending（申請中）・approved（承認）・rejected（却下）のどれか。新しく入る行は申請中から始まる
            $table->string('status')->default('pending');
            // 承認か却下が決まった日時。申請中のあいだは空
            $table->timestamp('decided_at')->nullable();
        });

        // W9 までに登録した行は、承認の仕組みがなかったころのもの。承認済みとして扱う
        DB::table('timetable_entries')->update(['status' => 'approved', 'decided_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('timetable_entries', function (Blueprint $table) {
            $table->dropColumn(['status', 'decided_at']);
        });
    }
};
