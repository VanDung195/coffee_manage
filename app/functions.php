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
                $items = MenuItem::query()->get();
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
                    ->orderBy('stt', 'asc')
                    ->paginate(20);
                // $tables = Table::query()->get();
                return $tables;
            }
        );
    }
}
//except table names: unknow, takeaway (using for change information table) 
if(!function_exists('getAndCacheAvailableTableNames')){
    function getAndCacheAvailableTableNames()
    {
        return cache()->remember(
            SystemCacheEnum::AVAILABLE_TABLE_NAME,
            84000 * 30,
            function()
            {
                $tables = Table::query()
                        ->orderBy('stt', 'asc')
                        ->get()
                        ->toArray();
                $tables = array_slice($tables, 6);

                return $tables;
            }
        );
    }
}
// if(!function_exists('getAndCacheShift'))
// {
//     function getAndCacheShift($shift_id)
//     {
//         return cache()->remember(
//             SystemCacheEnum::SHIFT,
//             84000 * 30,
//             function()
//             {
//                 $shift = Shift::query()
//                         ->where('id', $shift_id)
//                         ->value('description');
//                 return $shift;
//             }
//         );
//     }
// }
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