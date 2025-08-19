<?php

namespace App\Listeners;

use App\Events\DealStatusChanged;
use App\Mail\DealStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class DealStatusChangedListener
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DealStatusChanged $event): void
    {
        $deal = $event->deal;
        $newStatus = $event->newStatus;

        // processingまたはcompletedステータスになった場合のみメール送信
        if (in_array($newStatus, ['processing', 'completed'])) {
            // 商品出品者にメール送信
            $seller = $deal->purchase->item->user;

            Mail::to($seller->email)->send(new DealStatusChangedNotification($deal));
        }
    }
}
