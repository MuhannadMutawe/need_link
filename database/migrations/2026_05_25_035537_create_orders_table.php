<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('offer_id')->constrained('offers')->cascadeOnDelete();
            $table->foreignUlid('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('provider_id')->constrained('users')->cascadeOnDelete();
            
            $table->decimal('agreed_price', 12, 2);
            $table->char('currency_code', 3)->default('USD');
            
            $table->enum('status', ['pending', 'in_progress', 'completed_pending_confirmation', 'completed', 'cancelled', 'disputed'])->default('pending');
            $table->boolean('is_paid')->default(false);
            
            $table->timestamp('deadline_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            $table->unique('offer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
