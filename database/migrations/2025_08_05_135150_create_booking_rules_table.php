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
        Schema::create('booking_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); // Foreign key to teachers/users table
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('min_booking_notice')->default(24); // in hours
            $table->string('booking_window')->default(30); // in days
            $table->string('break_after_lesson')->default(15); // in minutes
            $table->boolean('accepting_new_students')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_rules');
    }
};
