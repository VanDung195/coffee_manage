<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Table extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'status',
        'is_paid',
        'floor',
        'invoice_id',
        'is_hidden',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id');
    }
}
