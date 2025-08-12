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
        Schema::create('teacher_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('paypal_email')->nullable();
            $table->string('payoneer_id')->nullable();
            $table->string('wise_account')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_routing_number')->nullable();
            $table->enum('preferred_method', ['paypal', 'payoneer', 'wise', 'bank'])->default('paypal');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->unique('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_payment_settings');
    }
};
