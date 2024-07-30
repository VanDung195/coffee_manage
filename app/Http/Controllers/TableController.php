<?php

namespace App\Http\Controllers;

use App\Enums\TableIsPaidEnum;
use App\Enums\TableStausEnum;
use App\Models\MenuItem;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Psr\Log\NullLogger;

class TableController extends Controller
{
    public function __construct()
    {
        $now = Carbon::now('Asia/Bangkok');
        $this->date = $now->format('Y-m-d');
        $this->hour = (int) $now->format('H'); 

        switch (true) {
            case $this->hour >= 6 && $this->hour <= 11:
                $this->shift_id = 1;
                break;
            case $this->hour >= 12 && $this->hour <= 17:
                $this->shift_id = 2;
                break;
            case $this->hour >= 18 && $this->hour <= 24:
                $this->shift_id = 3;
                break;
            default:
                $this->shift_id = 3; 
                break;
        }
    }

    public function index()
    {
        if(!user()){
            return redirect()->route('login');
        }
        if(user()->shift_id != $this->shift_id && user()->role != 0 && user()->role != 1)
        {
            return redirect()->route('user.index');
        }

        // $tables = Table::query()
        // ->orderBy('stt', 'asc')
        // ->paginate(13);
        $tables = getAndCacheTableName();
        $table_names_available = getAndCacheAvailableTableNames();
        // dd($tables);
        $items = getAndCacheMenuItems();
        $is_paids = TableIsPaidEnum::getKeys();
        return view('admin.index',[ 
            'tables' => $tables,
            'table_names_available' => $table_names_available,
            'items' => $items,
            'is_paids' => $is_paids,
        ]);
    }
    //delete invoice
    public function update(Request $request) 
    {
        $invoices = session()->get('invoice');
        if(isset($invoices[$request->table_id]))
        {
            unset($invoices[$request->table_id]);
            session()->put('invoice', $invoices);
        }else
        {
            Table::query()
            ->where('id', $request->table_id)
            ->update([
                'status' => TableStausEnum::getKey(1),
                'invoice_id' => 0,
            ]);
        }
        

        // $key_to_delete = null;
        // foreach ($invoices as $key => $value) {
        //     if($value['table_id'] === $request->table_name)
        //     {
        //         $key_to_delete = $key;
        //         break;
        //     }
        // }
        // if($key_to_delete != null)
        // {
        //     unset($invoices[$key_to_delete]);
        // }
        

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
