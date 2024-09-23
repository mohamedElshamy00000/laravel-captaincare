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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('photo');
            $table->string('name');
            $table->string('age')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default('1');
            $table->string('Latitude')->nullable();
            $table->string('Longitude')->nullable();

            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
