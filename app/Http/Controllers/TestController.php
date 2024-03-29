<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Enums\TableIsPaidEnum;
use App\Http\Controllers\TableController;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
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
                // $invoice_detail = DB::table('invoices')
                //                 ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                //                 ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                //                 ->select('user_id','created_at','checkin_time','checkout_time','menu_items.name')
                //                 ->get();
                // $invoices = DB::table('invoices')
                //         ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                //         ->join('menu_items', 'invoice_details.menu_item_id', '=', 'menu_items.id')
                //         ->select(
                //             'invoices.user_id',
                //             'invoices.created_at',
                //             'invoices.checkin_time',
                //             'invoices.checkout_time',
                //             DB::raw('GROUP_CONCAT(menu_items.name) as name')
                //         )
                //         ->groupBy('invoices.user_id', 'invoices.created_at', 'invoices.checkin_time', 'invoices.checkout_time')
                //         ->get();
                // $invoices = Invoice::with('details.menuItem')
                //         ->select('user_id', 'created_at', 'checkin_time', 'checkout_time')
                //         ->get();
                // $invoices = InvoiceDetail::with(['invoices', 'menuItem']);
                // $invoices = Invoice::with('details')->get();

                // $invoices = Invoice::with(['details' => function($query) {
                //     $query->select('invoice_id'); // Chọn các trường từ bảng InvoiceDetails
                // }, 'details.menuItem' => function($query) {
                //     $query->select('id', 'name'); // Chọn các trường từ bảng MenuItem
                // }])
                // ->select('user_id', 'created_at', 'checkin_time', 'checkout_time')
                // ->get();


                $invoices = Invoice::with(['details.menuItems'])
                    ->select('user_id', 'created_at', 'checkin_time', 'checkout_time')
                    ->get();

                $formattedInvoices = $invoices->map(function ($invoice) {
                    $nameArray = $invoice->details->map(function ($detail) {
                        return $detail->menuItems->name;
                    })->toArray();
                    
                    return (object)[
                        'user_id' => $invoice->user_id,
                        'created_at' => $invoice->created_at,
                        'checkin_time' => $invoice->checkin_time,
                        'checkout_time' => $invoice->checkout_time,
                        'name' => $nameArray
                    ];
                });
                // $invoice_details = InvoiceDetail::query()
                //                     ->with('menuItems')
                //                     ->get()->toArray();
                // $item = MenuItem::query()->get()->toArray();
                // $invoice = InvoiceDetail::query()->with('invoices')->get()->toArray();
                // $invoices = Invoice::with(['details.menuItems'])->get()->toArray();
                // $invoices = Invoice::with(['details.menuItems'])->get()->toArray();


                $table_invoice_id = Table::query()->where('invoice_id','<>', 0)->pluck('invoice_id')->toArray();
                // dd($table_invoice_id);
                //Eager Loading (eloquent relationships)
                $invoices = Invoice::with(['details' => function($query){
                        $query->select('invoice_id', 'menu_item_id', 'quantity');
                }, 'details.menuItems' => function($query){
                    $query->select('id','name');
                }])
                        // ->where('id',16)
                        ->whereIn('id',$table_invoice_id)
                        ->get()
                        ->toArray();
                // $invoiceDetails = InvoiceDetail::with('menuItems')->get()->toArray();
                // $invoicedetail = Invoice::with('details')->get()->toArray();
                
            }
            // dd($invoices);

            //xử lý mảng invoices
            // dd($invoices);
            // foreach ($invoices as $invoice) {
            //     dd($invoice);
            //     // var_dump($invoice);
            //     // echo $invoice['total_price'];
            //     echo $invoice['id'];
            //     foreach ($invoice['details'] as $invoice_detail) {
            //         // dd($invoice_detail['menu_items']['name']);
            //     }
            // }

            // dd($invoices[0]['details'][0]['menu_items']['name']);
            // dd($formattedInvoices);


            // $basicInfo = array_map(function($invoices) {
            //     return [
            //         'id' => $invoices['id'],
            //         'user_id' => $invoices['user_id'],
            //         'created_at' => $invoices['created_at'],
            //         'checkin_time' => $invoices['checkin_time'],
            //         'checkout_time' => $invoices['checkout_time'],
            //         'total_price' => $invoices['total_price'],
            //     ];
            // }, $invoices);

            // array_walk($yourArray, function(&$invoice) {
            //     $invoice['details'] = array_map(function($detail) {
            //         return [
            //             'invoice_id' => $detail['invoice_id'],
            //             'menu_item_id' => $detail['menu_item_id'],
            //             'quantity' => $detail['quantity'],
            //             'menu_item_name' => $detail['menu_items']['name']
            //         ];
            //     }, $invoice['details']);
            // });
            // dd($yourArray);
        }
        foreach ($invoices as $invoice) {
            // dd($invoice);
            // var_dump($invoice);
            echo $invoice['total_price'];
            // echo $invoice['id'];
            foreach ($invoice['details'] as $invoice_detail) {
                // dd($invoice_detail['menu_items']['name']);
            }
        }
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
