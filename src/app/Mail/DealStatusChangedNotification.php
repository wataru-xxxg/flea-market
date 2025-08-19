<?php

namespace App\Mail;

use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DealStatusChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $deal;
    public $seller;
    public $item;
    public $buyer;

    /**
     * Create a new message instance.
     */
    public function __construct(Deal $deal)
    {
        $this->deal = $deal;
        $this->seller = $deal->purchase->item->user;
        $this->item = $deal->purchase->item;
        $this->buyer = $deal->purchasedUser;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('取引ステータスが更新されました')
            ->view('emails.deal-status-changed')
            ->with([
                'deal' => $this->deal,
                'seller' => $this->seller,
                'item' => $this->item,
                'buyer' => $this->buyer,
            ]);
    }
}
