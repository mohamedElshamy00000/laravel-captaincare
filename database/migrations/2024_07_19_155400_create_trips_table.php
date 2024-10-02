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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->date('trip_date');
            $table->time('time');
            $table->longText('description');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['started', 'in_progress', 'completed'])->default('started');
            $table->enum('trip_type', ['morning', 'afternoon']);
            $table->timestamps();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->boolean('in_exam_period')->default(false);
            $table->unsignedBigInteger('school_class_id')->nullable();
            $table->foreign('school_class_id')->references('id')->on('school_classes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
