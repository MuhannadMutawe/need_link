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
            $table->foreignUlid('request_id')->nullable()->after('id')->constrained('requests')->cascadeOnDelete();
            $table->enum('order_type', ['service', 'product'])->default('service')->after('currency_code');
            $table->timestamp('confirm_deadline_at')->nullable()->after('completed_at');
            $table->integer('revision_count')->default(0)->after('confirm_deadline_at');
            $table->foreignUlid('cancelled_by')->nullable()->constrained('users')->nullOnDelete()->after('revision_count');
            $table->text('cancellation_reason')->nullable()->after('cancelled_by');
            $table->foreignUlid('closed_by')->nullable()->constrained('users')->nullOnDelete()->after('cancellation_reason');
        });

        // Let's populate the request_id from the offer table for existing records
        $orders = \DB::table('orders')->get();
        foreach ($orders as $order) {
            $offer = \DB::table('offers')->where('id', $order->offer_id)->first();
            if ($offer) {
                \DB::table('orders')->where('id', $order->id)->update(['request_id' => $offer->request_id]);
            }
        }

        Schema::create('order_deliveries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUlid('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('order_delivery_attachments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('delivery_id')->constrained('order_deliveries')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });

        Schema::create('order_revisions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUlid('requested_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->timestamps();
        });



        Schema::create('order_cancellation_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUlid('requested_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->enum('status', ['pending', 'agreed', 'rejected'])->default('pending');
            $table->foreignUlid('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            // Enforce only one pending request per order
            $table->unique(['order_id', 'status'], 'unique_pending_cancellation_request');
        });

        Schema::create('order_disputes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUlid('opened_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->foreignUlid('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_note')->nullable();
            $table->timestamps();

            // Enforce only one open dispute per order
            $table->unique(['order_id', 'status'], 'unique_open_dispute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_disputes');
        Schema::dropIfExists('order_cancellation_requests');
        Schema::dropIfExists('order_revisions');
        Schema::dropIfExists('order_delivery_attachments');
        Schema::dropIfExists('order_deliveries');
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropForeign(['closed_by']);
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn([
                'request_id',
                'order_type',
                'confirm_deadline_at',
                'revision_count',
                'cancelled_by',
                'cancellation_reason',
                'closed_by'
            ]);
        });
    }
};
