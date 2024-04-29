<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoicePlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tableId;
    public $invoiceDetails;
    public $totalPrice;
    public $createdAt;
    public $checkinTime;
    public $checkoutTime;
    public $isPaid;

    public function __construct($tableId, $invoiceDetails, $totalPrice, $createdAt, $checkinTime, $checkoutTime, $isPaid)
    {
        $this->tableId = $tableId;
        $this->invoiceDetails = $invoiceDetails;
        $this->totalPrice = $totalPrice;
        $this->createdAt = $createdAt;
        $this->checkinTime = $checkinTime;
        $this->checkoutTime = $checkoutTime;
        $this->isPaid = $isPaid;
    }
    // public function __construct($invoice_detail)
    // {
    //     $this->invoice_detail = $invoice_detail;
    // }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        // dd(111111111111111);
        \Log::debug("abc");
        return new Channel('order-channel');
    }
}
