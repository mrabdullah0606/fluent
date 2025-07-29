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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('documents')->nullable();
            $table->string('hobbies')->nullable();
            $table->string('certifications')->nullable();
            $table->string('experience')->nullable();
            $table->string('teaching_style')->nullable();
            $table->string('about_me')->nullable();
            $table->foreignId('language_id')->nullable()->constrained('languages')->onDelete('set null');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
