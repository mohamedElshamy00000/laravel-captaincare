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
        Schema::create('subscription_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('due_date');
            $table->integer('amount');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('refunded')->default(0);
            $table->string('payment_way')->nullable();
            $table->text('transaction_id')->nullable();
            $table->string('discount')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('child_id')->nullable();
            $table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('fathers')->onDelete('cascade');
            $table->integer('plan_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_invoices');
    }
};
