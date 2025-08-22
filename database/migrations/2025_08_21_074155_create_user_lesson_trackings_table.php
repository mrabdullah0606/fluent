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
        Schema::create('user_lesson_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('payment_id');
            $table->string('payment_type');
            $table->string('package_summary');
            $table->integer('total_lessons_purchased')->default(0);
            $table->integer('lessons_taken')->default(0);
            $table->integer('lessons_remaining')->default(0);
            $table->decimal('price_per_lesson', 10, 2)->nullable();
            $table->enum('status', ['active', 'completed', 'expired', 'cancelled'])->default('active');
            $table->date('purchase_date');
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->index(['student_id', 'teacher_id']);
            $table->index(['student_id', 'status']);
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_lesson_tracking');
    }
};
