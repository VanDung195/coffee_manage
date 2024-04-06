<?php

use App\Enums\SystemCacheEnum;
use App\Enums\UserRoleEnum;
use App\Models\MenuItem;

if(!function_exists('getAndCacheMenuItems')){
    function getAndCacheMenuItems(): object
    {
        return cache()->remember(
            SystemCacheEnum::MENU_ITEMS,
            84000*30,
            function(){
                $items = MenuItem::query()->get();
                return $items;
            }
        );
    }
}
if(!function_exists('user')) {
    function user(){
        return auth()->user();
    }
}

if(!function_exists('isAdmin')) {
    function isAdmin(){
        return user() && user()->role === UserRoleEnum::ADMIN;
    }
}
if(!function_exists('isManager')) {
    function isManager() {
        return user() && user()->role === UserRoleEnum::MANAGER;
    }
}
if(!function_exists('getRoleByValue')){
    function getRoleByValue($value){
        return UserRoleEnum::getKey($value);
    }
}