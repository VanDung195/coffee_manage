<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    public $timestamps = false;

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    protected static function booted()
    {
        static::creating(static function($object){
            $object->user_id = 1;
        });
    }
}
