<?php

namespace App\Http\Controllers;

use App\Enums\TableIsPaidEnum;
use App\Models\MenuItem;
use App\Models\Table;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::query()
        ->orderBy('stt', 'asc')
        ->paginate(10);
        $items = MenuItem::query()
                    ->get();
        foreach ($tables as $table) {
            printf($table->name);
        }
        dd();
        $is_paids = TableIsPaidEnum::getKeys();
        return view('admin.index',[ 
            'tables' => $tables,
            'items' => $items,
            'is_paids' => $is_paids,
        ]);
    }
}
