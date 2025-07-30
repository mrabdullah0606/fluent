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
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('meeting_id');
            $table->string('host_id');
            $table->string('topic');
            $table->dateTime('start_time');
            $table->integer('duration');
            $table->string('timezone')->nullable();
            $table->string('join_url');
            $table->mediumText('start_url');
            $table->string('password')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_meetings');
    }
};
