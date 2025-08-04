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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('summary');
            $table->string('type')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('fee', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('payment_method'); // e.g. stripe, demo
            $table->string('status')->default('pending'); // e.g. pending, successful
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
