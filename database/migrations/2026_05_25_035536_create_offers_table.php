<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();

            $table->text('message');
            $table->decimal('proposed_price', 12, 2);
            $table->char('currency_code', 3)->default('USD');

            $table->integer('estimated_time');
            $table->enum('time_unit', ['hours', 'days', 'weeks']);
            $table->timestamp('expires_at')->nullable();

            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');

            $table->timestamps();

            $table->unique(['request_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
