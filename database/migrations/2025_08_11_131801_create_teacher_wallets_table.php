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
        Schema::create('teacher_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->decimal('total_earned', 10, 2)->default(0.00);
            $table->decimal('total_withdrawn', 10, 2)->default(0.00);
            $table->timestamps();

            $table->unique('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_wallets');
    }
};
