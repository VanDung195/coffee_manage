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
        dd(1);
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
        // return view('testmodal');

        // dd(UserRoleEnum::getRoleForRegister());
        // dd(user());
        // $now = Carbon::now('Asia/Bangkok');
        // dd($now->format('Y:m:d H:i:s'));
        // dd(uniqid('vcl', true));

        // $menu_items = getAndCacheMenuItems();
        // foreach ($menu_items as $each) {
        //     $arr[$each['id']] = [
        //         'name' => $each['name'],
        //         'y' => 0,
        //         'drilldown' => $each['id'],
        //     ];
        //     $arr2[$each['id']] = [
        //         'name' => $each['name'],
        //         'id' => $each['id'],
        //         'data' => [],
        //     ];
        // }
        // dd($arr, $arr2);

        // dd(date('Y-m-d'));
        // $start_date = strtotime('2024-04-20');
        // $end_date = strtotime('2024-04-25');
        // $start_date = '2024-03-01';
        // $end_date = '2024-04-02';
        // $so_ngay = ($end_date - $start_date);

        // dd($so_ngay);
        // $date_arr = [];
        // $current_date = $start_date;

        // while($current_date <= $end_date)
        // {
        //     $date_arr[$current_date] = 0;

        //     $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        // }

        // dd($date_arr);

        // $x = strtotime("01-03-2024");
        // $n = date("m-Y",strtotime("+1 month",$x));
        // // echo $n;
        // dd($n);

        ////////////////////////////////////////////////////test invoice create
        // dd(session()->get('invoice'));
        // $invoices = session()->get('invoice');
        // dd($invoices);
        // foreach($invoices as $item)
        // {
        //     dd($item);
        // }
        // session()->flush();
        // dd(uniqid('ic', true));

        // cache()->clear();
        // $array = Table::query()->orderBy('stt', 'asc')->pluck('name')
        //             ->toArray();
        // array_shift($array);
        // dd($array);
        // dd(getAndCacheTableName());
        // $table = getAndCacheTableName();
        // foreach($table as $each)
        // {
        //     dd($each->name);
        // }

        // Xoá session tại vị trí cụ thể
        // $table_id_to_delete = 'T1_1'; // Đặt table_id cần xoá
        // session()->forget('invoice.' . $table_id_to_delete);
        // $invoices = session()->get('invoice');
        // // dd($invoices['T1_2']);
        // unset($invoices['T1_2']);
        // // session()->forget('invoice.' . $table_id_to_delete);

        // session()->put('invoices', $invoices);
        // dd(session()->get('invoice'));
        // cache()->clear();
        // Lấy toàn bộ dữ liệu từ session

        // $invoices = session()->get('invoice');
        // if(isset($invoices["T1_2"]))
        // {
        //     unset($invoices['T1_2']);
        //     // dd($invoices, 1);

        //     session()->put('invoice', $invoices);
        // }

        // dd($invoices['T1_2']);

        // dd(Invoice::query()->get()->toArray())
        // dd(session()->get('invoice'));

        /*
        $tables = Table::query()
        ->orderBy('stt', 'asc')
        ->get()
        ->toArray();
        // dd($tables);
        // $table = Table::query()->get()
        // ->orderBy('stt','asc')
        // ->toArray();
        // array_shift($tables);
        $tables = array_slice($tables, 3);
        // $tables = array_slice($tables, 0, 2);
        dd($tables);


        $tables = Table::query()
            ->orderBy('stt', 'asc')
            ->get()
            ->toArray();

        // In ra mảng ban đầu
        echo "Mảng ban đầu:\n";
        // print_r($tables);

        if (count($tables) > 2) {
            // Loại bỏ hai phần tử đầu tiên của mảng
            $tables = array_slice($tables, 2);
            echo "Mảng sau khi loại bỏ hai phần tử đầu tiên:\n";
            dd($tables);
        } else {
            echo "Mảng không có đủ phần tử để loại bỏ hai phần tử đầu tiên.\n";
        } */



        // dd(session()->get('invoice'));
        // $invoice = Invoice::query()->pluck('created_at')->toArray();
        // foreach($invoice as $each)
        // {
        //     // dd($each);
        //     // dd(date_format($each,'d-m-Y'));
        //     dd(date('d-m-Y', strtotime($each)));
        // }
        // // dd($invoice);
        // dd(array_values($invoice));
        // $ipAddress = $_SERVER['REMOTE_ADDR'];
        // dd($ipAddress);
        // $test = 30000;
        // $formattedAmount = number_format($test, 0, ',', '.');
        // dd($formattedAmount);
        // $table_names = getAndCacheAvailableTableNames();
        // // dd($table_names);
        // // dd($table_names);
        // foreach($table_names as $item)
        // {
        //     dd($item['name']);
        // }

        //https://stackoverflow.com/questions/240660/replace-keys-in-an-array-based-on-another-lookup-mapping-array
//         $invoice = session()->get('invoice');
//         dd($invoice);
//         $old_key = 'T1_2';
//         $new_key = 'T1_9';

//         if(!array_key_exists($old_key, $invoice))
//             return $invoice;

//         $keys = array_keys($invoice);
//         // dd($keys);
//         // dd(array_search($old_key, $keys));
//         $keys[array_search($old_key, $keys)] = $new_key;
//         // dd($keys);

//         // return array_combine($keys,$invoice);
//         $invoice =  array_combine($keys,$invoice);
//         // dd($array_cb[$new_key]['table_id']);
//          $invoice[$new_key]['table_id'] = $new_key;
//          \session()->put('invoice', $invoice);
// //         dd($invoice);


        // $table_invoice = Table::query()
        //     ->whereIn('name', ['T1_2', 'T1_3'])
        //     ->pluck('invoice_id', 'name');
        // dd($table_invoice);
        // session()->flush();
        // $position = Position::find('4');

        // dd(Str::ucfirst('ADMIN'));
        // dd(1);
        // dd($position->salary);
        
        // cache()->clear();
        // Invoice::query()
        //         ->where('table_id', 'takeaway')
        //         ->update([
        //             'table_id' => 5,
        //         ]);
        // dd(1);
        // dd(getAndCacheAvailableTableNames());

        // Xóa toàn bộ session
        // session()->flush();
        // dd(session()->get('invoice'));

        // Kiểm tra session ngay sau khi xóa
        // $sessionData = session()->all();
        // dd('After flush', $sessionData);

        // Đặt session nếu không có 'invoice'

        /*
        $invoices = session()->get('invoice');
        // unset($invoices['T1_2']);
        dd($invoices);
        dd($invoices);
        session()->put('invoice', $invoices);
        dd(1);
        if (!session()->has('invoice')) {
            session()->put('invoice', []);
        }

        // Lấy và debug session 'invoice'
        $invoice = session()->get('invoice');
        dd('After setting invoice', $invoice);

        session()->forget('invoice');
        dd(session()->all());


        if (!session()->has('invoice')) {
            session()->put('invoice', []);
        }
        $invoice = session()->get('invoice');
        dd($invoice);

        dd(session()->get('invoice'));

        cache()->clear();

        ///UPDATE STT LEN 1
        $stt = Table::query()
                ->where('name', 'takeaway')
                // ->select('stt');
                ->pluck('stt');
        $stt1 = Table::query()
                ->where('stt', '>=', $stt)
                ->orderBy('stt', 'desc')
                ->pluck('stt');
        dd($stt1);
        foreach ($stt1 as $key => $value) {
            // dd($value);
            Table::query()
                ->where('stt', $value)
                ->increment('stt');
        }
        dd($stt1);*/
        // dd(session()->all());
        // dd(user()->id);
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
        // dd(1);
        dd($invoices);
        // session()->flush();

        // cache()->clear();
        dd(session()->get('invoice'));
        $test = SalaryInformation::query()
                ->where('id', 11)
                ->first();
        dd($test->created_at->diffInDays(Carbon::now()));

    }
    public function test_view()
    {
        // return view('qr.success');
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
