<?php

use App\Enums\SystemCacheEnum;
use App\Enums\UserRoleEnum;
use App\Models\MenuItem;
use App\Models\Position;
use App\Models\Shift;
use App\Models\Table;

if(!function_exists('getAndCacheMenuItems')){
    function getAndCacheMenuItems(): object
    {
        return cache()->remember(
            SystemCacheEnum::MENU_ITEMS,
            84000*30,
            function(){
                $items = MenuItem::query()
                        ->where('is_hidden', false)
                        ->get();
                return $items;
            }
        );
    }
}
if(!function_exists('getAndCacheTableName')){
    function getAndCacheTableName()
    {
        return cache()->remember(
            SystemCacheEnum::TABLE_NAMES,
            84000*30,
            function()
            {
                $tables = Table::query()
                    ->paginate(20);
                // $tables = Table::query()->get();
                return $tables;
            }
        );
    }
}
//except table names: unknow, takeaway (use for change information table) 
if(!function_exists('getAndCacheAvailableTableNames')){
    function getAndCacheAvailableTableNames()
    {
        return cache()->remember(
            SystemCacheEnum::AVAILABLE_TABLE_NAME,
            84000 * 30,
            function()
            {
                $tables = Table::query()
                        ->get()
                        ->toArray();
                $tables = array_slice($tables, 6);

                return $tables;
            }
        );
    }
}
if(!function_exists('getAndCacheShift'))
{
    function getAndCacheShift()
    {
        return cache()->remember(
            SystemCacheEnum::SHIFT,
            84000 * 30,
            function()
            {
                $shift = Shift::query()
                        ->get();
                return $shift;
            }
        );
    }
}
if(!function_exists('getAndCachePositions'))
{
    function getAndCachePositions()
    {
        return cache()->remember(
            SystemCacheEnum::POSITIONS, 
            84000 * 30,
            function()
            {
                $positions = Position::query()
                            ->where('name', '<>', 'Admin')
                            ->get();
                return $positions;
            }
        );
    }
}
if(!function_exists('getAndCacheInvalidTableForQROrder'))
{
    function getAndCacheInvalidTableForQROrder()
    {
        return cache()->remember(
            SystemCacheEnum::INVALIDTABLE,
            84000 * 30,
            function() 
            {
                //query builder
                // $invalid_table_name = Table::query()
                //         ->where(function($query) {
                //             $query->where('name', 'not like', 'unknow%')
                //             ->orWhere('name', '<>', 'takeaway');
                //         })->pluck('name')->toArray();
                //eloquent
                $invalid_table_id = Table::where(function($query) {
                    $query->where('name', 'NOT LIKE', 'unknow%')
                          ->where('name', '<>', 'takeaway');
                })->pluck('id')->toArray();

                return $invalid_table_id;
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
if(!function_exists('isCashier')) {
    function isCashier() {
        return user() && user()->role === UserRoleEnum::CASHIER;
    }
}
if(!function_exists('getRoleByValue')){
    function getRoleByValue($value){
        return UserRoleEnum::getKey($value);
    }
}