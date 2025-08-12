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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['paypal', 'payoneer', 'wise', 'bank']);
            $table->json('account_details');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('request_date')->useCurrent();
            $table->timestamp('processed_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'status']);
            $table->index(['status', 'request_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
