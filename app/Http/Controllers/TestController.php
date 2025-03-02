<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Enums\TableIsPaidEnum;
use App\Enums\UserRoleEnum;
use App\Http\Controllers\TableController;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Position;
use App\Models\SalaryInformation;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Telescope\Watchers\ViewWatcher;

class TestController extends Controller
{
    public function test()
    {
        return view('qr.success');
        dd(1);
        $tables = Table::query()->get();
        foreach ($tables as $table) {
            if($table->invoice_id != 0)
            {
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


                $table_invoice_id = Table::query()->where('invoice_id','<>', 0)->pluck('invoice_id')->toArray();
                //Eager Loading (eloquent relationships)
                $invoices = Invoice::with(['details' => function($query){
                        $query->select('invoice_id', 'menu_item_id', 'quantity');
                }, 'details.menuItems' => function($query){
                    $query->select('id','name');
                }])
                        ->whereIn('id',$table_invoice_id)
                        ->get()
                        ->toArray();
            }
        }
        foreach ($invoices as $invoice) {
            echo $invoice['total_price'];
            foreach ($invoice['details'] as $invoice_detail) {
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
        cache()->clear();
        dd(session()->get('invoice'));

        cache()->clear();

        $tables = Table::query()
                ->whereRaw("name REGEXP '^T[0-9]+_[0-9]+$'")
                ->get();
        dd($tables);
        $tables = Table::where(DB::raw("name REGEXP '^T[0-9]+_[0-9]+$'"))
                            ->get();
        dd($tables);
        $test = session()->get('invoice');
        unset($test['6']);
        session()->put('invoice', $test);
        dd(1);
        $invoices = session()->get('invoice');
        if(isset($invoices[6]))
        {
            unset($invoices[6]);
            session()->put('invoice', $invoices);
        }
        dd($invoices);
        dd(1);
        $invoices = session()->get('invoice');
        unset($invoices[6]);
        session()->put('invoice', $invoices);
        dd($invoices);
        dd(session()->get('invoice'));
        $test = SalaryInformation::query()
                ->where('id', 11)
                ->first();
        dd($test->created_at->diffInDays(Carbon::now()));

    }
    public function test_view()
    {
        return view('testmodal');
    }

    public function success()
    {
        return view('qr.success');
    }

    public function test_print()
    {
        return view('qr.success');
    }
    public function test_invoice_print()
    {
        return view('success');
    }
}
