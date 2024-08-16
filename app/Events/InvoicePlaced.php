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
    public $table_name;
    public $details;
    public $total_price;
    public $created_at;
    public $checkin_time;
    public $checkout_time;
    public $customer_payment_response;
    public $remaining_money_response;
    public $customer_payment;
    public $remaining_money;
    public $is_paid;
    public $is_qr;
    public $user_id;
    // public $invoice_id;

    public function __construct($table_id, $table_name, $details, $total_price, $created_at, $checkin_time, 
                                $checkout_time, $customer_payment_response, $remaining_money_response, 
                                $customer_payment, $remaining_money, $is_paid, $is_qr, $invoice_id)
    {
        $this->table_id = $table_id;
        $this->table_name = $table_name;
        $this->details = $details;
        $this->total_price = $total_price;
        $this->created_at = $created_at;
        $this->checkin_time = $checkin_time;
        $this->checkout_time = $checkout_time;
        $this->customer_payment = $customer_payment_response;
        $this->remaining_money = $remaining_money_response;
        $this->customer_payment = $customer_payment;
        $this->remaining_money = $remaining_money;
        $this->is_paid = $is_paid;
        $this->is_qr = $is_qr;
        // $this->user_id = $user_id;
        $this->invoice_id = $invoice_id;

        // session()->put('order', [
        //     'table_id' => $this->table_id,
        //     'details' => $this->details,
        //     'total_price' => $this->total_price,
        //     'created_at' => $this->created_at,
        //     'checkin_time' => $this->checkin_time,
        //     'checkout_time' => $this->checkout_time,
        //     'customer_payment' => $this->customer_payment,
        //     'remaining_money' => $this->remaining_money,
        //     'is_paid' => $this->is_paid,
        //     'is_qr' => $this->is_qr,
        // ]);
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
