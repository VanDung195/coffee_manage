<?php

namespace App\Http\Controllers;

use App\Enums\TableIsPaidEnum;
use App\Enums\TableStausEnum;
use App\Models\MenuItem;
use App\Models\Table;
use Illuminate\Http\Request;

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
            
        }
        // dd();
        $is_paids = TableIsPaidEnum::getKeys();
        return view('admin.index',[ 
            'tables' => $tables,
            'items' => $items,
            'is_paids' => $is_paids,
        ]);
    }
    public function update(Request $request) 
    {
        Table::query()
        ->where('name',$request->table_name)
        ->update([
            'status' => TableStausEnum::getKey(1),
            'invoice_id' => 0,
        ]);
        return 1;
    }
}
