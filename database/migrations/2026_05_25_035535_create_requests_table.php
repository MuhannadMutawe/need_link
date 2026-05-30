<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Ownership
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            
            // Content
            $table->string('title')->index();
            $table->text('description');
            
            // Pricing
            $table->enum('pricing_type', ['fixed', 'hourly', 'negotiable']);
            $table->decimal('budget', 12, 2)->nullable();
            $table->char('currency_code', 3)->default('USD');
            
            // Visibility
            $table->timestamp('published_at')->nullable()->useCurrent();
            $table->timestamp('expires_at')->nullable();
            
            // Lifecycle
            $table->enum('status', ['draft', 'open', 'assigned', 'completed', 'cancelled', 'closed'])->default('open');
            
            // Metrics
            $table->unsignedInteger('applicant_count')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('bookmarks_count')->default(0);
            $table->decimal('popularity_score', 10, 2)->default(0);
            
            // Admin
            $table->foreignUlid('closed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
