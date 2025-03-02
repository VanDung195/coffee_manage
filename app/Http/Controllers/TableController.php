<?php

namespace App\Http\Controllers;

use App\Enums\TableIsPaidEnum;
use App\Enums\TableStausEnum;
use App\Models\MenuItem;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $tables = getAndCacheTableName();
        $table_names_available = getAndCacheAvailableTableNames();
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
        return 1;
    }

    public function qr_show(Request $request)
    {
        $table_name = $request->table_name;
        $valid_table = Table::query()
                    ->where('name', $table_name)
                    ->first();
        $valid_table_id = getAndCacheInvalidTableForQROrder();
        if(!is_null($valid_table) && in_array($valid_table->id, $valid_table_id))
        {
            $items = getAndCacheMenuItems();
            return view('qr.index',[
                'items' => $items,
                'table_name' => $table_name,
                'table_id' => $valid_table->id,
            ]);
        }
        return view('error_page.notfound',[
            'error_title' => 'Không tìm thấy bàn: '.$table_name,
            'error_message' => 'Quý khách vui lòng không được sửa đường dẫn!!!!',
        ]);
    }
}
