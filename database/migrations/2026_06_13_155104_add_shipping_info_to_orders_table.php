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
        Schema::table('orders', function (Blueprint $table) {
            // Product order shipping info stored directly on the order
            $table->string('carrier')->nullable()->after('is_paid');
            $table->string('tracking_number')->nullable()->after('carrier');
            $table->string('tracking_url')->nullable()->after('tracking_number');
            $table->boolean('is_shipped')->default(false)->after('tracking_url');
            $table->timestamp('shipped_at')->nullable()->after('is_shipped');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['carrier', 'tracking_number', 'tracking_url', 'is_shipped', 'shipped_at']);
        });
    }
};
