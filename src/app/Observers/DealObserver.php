<?php

namespace App\Observers;

use App\Events\DealStatusChanged;
use App\Models\Deal;

class DealObserver
{
    /**
     * Handle the Deal "updated" event.
     */
    public function updated(Deal $deal): void
    {
        // ステータスが変更された場合のみイベントを発火
        if ($deal->wasChanged('status')) {
            $oldStatus = $deal->getOriginal('status');
            $newStatus = $deal->status;

            event(new DealStatusChanged($deal, $oldStatus, $newStatus));
        }
    }
}
