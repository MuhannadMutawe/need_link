<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoConfirmOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-confirms orders that have passed their confirmation deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = \App\Models\Order::where('status', 'completed_pending_confirmation')
            ->where('confirm_deadline_at', '<=', now())
            ->doesntHave('disputes', 'and', function($query) {
                $query->where('status', 'open');
            })
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'confirm_deadline_at' => null,
                ]);
                $order->serviceRequest->update(['status' => 'completed']);
            });
            $count++;
            $this->info("Auto-confirmed order {$order->id}");
        }

        $this->info("Successfully auto-confirmed {$count} orders.");
    }
}
