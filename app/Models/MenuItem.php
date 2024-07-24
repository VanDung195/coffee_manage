<?php

namespace App\Models;

use App\Enums\SystemCacheEnum;
use App\Enums\SystemCacheKeyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_category_id',
        'name',
        'price',
        'is_hidden'
    ];

    // protected static function booted(): void
    // {
    //     static::saved(static function($menu_item) {
    //         Cache::forget(SystemCacheEnum::MENU_ITEMS);
    //         // cache()->forget(SystemCacheKeyEnum::MENU_ITEMS);
    //         getAndCacheMenuItems();
    //     });
    // }
    protected static function booted(): void
    {
        static::updated(function ($menuItem) {
            Cache::forget(SystemCacheEnum::MENU_ITEMS);
            getAndCacheMenuItems();
        });

        static::created(function ($menuItem) {
            Cache::forget(SystemCacheEnum::MENU_ITEMS);
            getAndCacheMenuItems();
        });

        static::deleted(function ($menuItem) {
            Cache::forget(SystemCacheEnum::MENU_ITEMS);
            getAndCacheMenuItems();
        });
    }
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
    public function getPriceForEditAttribute()
    {
        $price = ($this->price) / 1000;
        return $price;
    }
}
