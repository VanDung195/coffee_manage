<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Enums\TableIsPaidEnum;
use App\Http\Controllers\TableController;
use App\Models\Attendance;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        // $item = MenuItem::query();
        // $items = MenuItem::query()
        //             ->where('name', 'like', '%' . 'Sinh tố' . '%')->get();
        // // dd($items);
        // foreach ($items as $key => $value) {
        //     // dd($value->name, $value->id);
        //     echo $value;
        // }
        // return TableController::class;
        // $isPaids = TableIsPaidEnum::getKeys();
        // dd($isPaids);
        // foreach ($isPaids as $key => $value) {
        //     dd($key,$value);
        // }

        // return Table::query()->get('name');
        $tables = Table::query()->get();
        foreach ($tables as $table) {
            if($table->invoice_id != 0)
            {
                // $invoice_detail = Invoice::query()->where('id', $table->invoice_id)->first();
                $invoice_detail = DB::table('invoices')
                                ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                                ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                                ->select('user_id','created_at','checkin_time','checkout_time','menu_items.name')
                                ->get();
            }
        }
        dd($invoice_detail);
    }
    public function create() {
        return view('testee');
    }
    public function test2(Request $request)
    {
        $test = MenuItem::query()->where('id', 1)->first()->toArray();
        dd($test['name']);
    }
    public function test3() {
        return view('testmodal');
    }
}
