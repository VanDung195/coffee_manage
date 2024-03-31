<?php

use App\Enums\UserRoleEnum;

if(!function_exists('getAndCacheMenuItems')){
    function getAndCacheMenuItems()
    {
        
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