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
        Schema::create('admin_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->decimal('total_earned', 15, 2)->default(0.00);
            $table->decimal('total_withdrawn', 15, 2)->default(0.00);
            $table->timestamps();

            $table->unique('admin_id');
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_wallets');
    }
};
