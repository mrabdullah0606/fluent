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
        Schema::create('teacher_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); // Foreign key to teachers/users table
            $table->date('available_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->string('status')->default('available'); // available, booked, unavailable
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['teacher_id', 'available_date']);
            $table->index(['available_date', 'is_available']);

            // Foreign key constraint (adjust table name as needed)
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint to prevent duplicate time slots
            $table->unique(
                ['teacher_id', 'available_date', 'start_time', 'end_time'],
                'teacher_avail_short_unique' // Shortened name
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_availabilities');
    }
};
