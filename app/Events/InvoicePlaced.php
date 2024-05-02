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

    public $table_id;
    public $details;
    public $total_price;
    public $created_at;
    public $checkin_time;
    public $checkout_time;
    public $is_paid;

    public function __construct($table_id, $details, $total_price, $created_at, $checkin_time, $checkout_time, $is_paid)
    {
        $this->table_id = $table_id;
        $this->details = $details;
        $this->total_price = $total_price;
        $this->created_at = $created_at;
        $this->checkin_time = $checkin_time;
        $this->checkout_time = $checkout_time;
        $this->is_paid = $is_paid;
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
