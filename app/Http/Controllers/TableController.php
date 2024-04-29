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
        if(!user()){
            return redirect()->route('login');
        }
        $tables = Table::query()
        ->orderBy('stt', 'asc')
        ->paginate(10);
        $items = getAndCacheMenuItems();
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
    public function qr_show(Request $request)
    {
        $table_id = $request->table_id;
        $items = getAndCacheMenuItems();
        // $table_name = "T1_1";
        return view('qr.index',[ 
            'items' => $items,
            'table_name' => $table_id,
        ]);
    }
}
