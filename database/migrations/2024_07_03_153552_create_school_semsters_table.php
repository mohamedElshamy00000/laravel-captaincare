<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_semsters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('semester');
            $table->date('study_start');
            $table->date('study_end');
            $table->date('exam_start');
            $table->date('exam_end');
            $table->date('holiday_start');
            $table->date('holiday_end');
            $table->integer('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_semsters');
    }
};
