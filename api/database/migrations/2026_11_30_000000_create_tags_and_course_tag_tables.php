<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        // 授業とタグをつなぐ中間テーブル。1行が「この授業にこのタグが付いている」を表す
        Schema::create('course_tag', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained();
            // タグを消したら、そのタグの付いた行も消える
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            // 同じ授業に同じタグを2回付けられないようにする
            $table->primary(['course_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_tag');
        Schema::dropIfExists('tags');
    }
};
