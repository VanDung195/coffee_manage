<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\SoftDeletes;

class MenuCategory extends Model
{
    use HasFactory;
    // use SoftDeletes;
    
    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_hidden'
    ];
    // public function menu_items(): HasMany
    // {
    //     return $this->hasMany(MenuItem::class);
    // }
}
