<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // だれが書いたか。ユーザーを消したら、その人のレビューも消える
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // どの授業のレビューか（courses は Laravel のマイグレーションの外で作ったテーブル）
            $table->foreignId('course_id')->constrained();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
