<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_disputes', function (Blueprint $table) {
            $table->text('counter_reason')->nullable()->after('reason');
            $table->timestamp('counter_reason_submitted_at')->nullable()->after('counter_reason');
        });
    }

    public function down(): void
    {
        Schema::table('order_disputes', function (Blueprint $table) {
            $table->dropColumn(['counter_reason', 'counter_reason_submitted_at']);
        });
    }
};
