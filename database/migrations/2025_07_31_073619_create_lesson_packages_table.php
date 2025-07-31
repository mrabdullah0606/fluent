<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lesson_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->integer('package_number');
            $table->string('name');
            $table->integer('number_of_lessons');
            $table->integer('duration_per_lesson');
            $table->decimal('price', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('teacher_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_packages');
    }
};
