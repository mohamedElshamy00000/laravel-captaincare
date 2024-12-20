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
        Schema::create('child_monthly_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);

            $table->timestamps();
            $table->unique(['child_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_monthly_prices');
    }
};
