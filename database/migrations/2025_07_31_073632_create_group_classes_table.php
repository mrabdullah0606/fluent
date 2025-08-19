<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_per_class');
            $table->integer('lessons_per_week');
            $table->integer('max_students');
            $table->decimal('price_per_student', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('teacher_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_classes');
    }
};
