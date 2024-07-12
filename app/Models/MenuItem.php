<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_category_id',
        'name',
        'price',
        'deleted_at'
    ];

    public $timestamps = false;
    public function getPriceVNDAttribute()
    {
        $price_vnd = number_format($this->price, 0, ',', '.');
        return $price_vnd;
    }
    public function menu_category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}
