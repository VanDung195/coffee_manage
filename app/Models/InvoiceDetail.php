<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InvoiceDetail extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'menu_item_id',
        'quantity',
    ];

    public function invoices(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function menuItems(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
        // return $this->belongsTo(MenuItem::class);
    }
}
