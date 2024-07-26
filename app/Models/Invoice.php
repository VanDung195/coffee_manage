<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'created_at',
        'checkin_time',
        'checkout_time',
        'total_price',
        'customer_payment',
        'remaining_money',
        'table_id',
    ];

    public $timestamps = false;

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }
    public function tables(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }

    public function getTotalPriceFormattedAttribute()
    {
        return number_format($this->total_price, 0, ',', '.');
    }

    public function getCustomerPaymentFormattedAttribute()
    {
        return number_format($this->customer_payment, 0, ',', '.');
    }

    public function getRemainingMoneyFormattedAttribute()
    {
        return number_format($this->remaining_money, 0, ',', '.');
    }

    protected static function booted()
    {
        static::creating(static function($object){
            $object->user_id = user()->id;
        });
    }
}
