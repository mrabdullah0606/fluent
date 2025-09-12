<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_class_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_class_id')->constrained()->onDelete('cascade');
            $table->date('day');
            $table->time('time')->nullable();
            $table->timestamps();
            $table->index('group_class_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_class_days');
    }
};
