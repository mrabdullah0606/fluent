<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('meeting_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('student_attended')->default(false);
            $table->boolean('teacher_attended')->default(false);
            $table->timestamp('student_confirmed_at')->nullable();
            $table->timestamp('teacher_confirmed_at')->nullable();
            $table->boolean('payment_released')->default(false);
            $table->timestamp('payment_released_at')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'disputed'])->default('pending');
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('admin_approved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('meeting_id')->references('id')->on('zoom_meetings')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');

            // Indexes
            $table->index(['student_id', 'teacher_id']);
            $table->index(['meeting_id', 'status']);
            $table->index('payment_released');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_attendances');
    }
};
