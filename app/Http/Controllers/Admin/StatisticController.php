<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class StatisticController extends Controller
{
    use ResponseTrait;
    public function statistic_day_i(){
        return view('admin.statistic.statistic_day');
    }
    public function statistic_day(Request $request){
        // $today = date('Y-m-d');
        // $today = $request->all() ? $request->all() : date('Y-m-d');
        $today = $request->input('date_input', date('Y-m-d'));

        // if($request->all() == null) {
        //     $today =  date('Y-m-d');
        // }else{
        //     $today = $request->all();
        // }
        //  $today = date('2024-04-09');
        // $today = '2024-04-09';

        $menu_items_name = MenuItem::query()->pluck('name');
        $arrX = [];
        
        $menu_items = Invoice::selectRaw('year(created_at) as year, month(created_at) as month, 
                                        day(created_at) as day,menu_items.name,sum(invoice_details.quantity) as quantity, 
                                        sum(invoice_details.quantity*menu_items.price) as total_price')
                ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                ->whereDate('created_at', $today)
                ->groupBy('year', 'month', 'day','menu_items.name')
                ->get();
        // dd($menu_items);
        $arrX = [];
        $arrY = [];
        foreach($menu_items_name as $data) 
        {
            $arrX[$data] = 0;
            $arrY[$data] = 0;
        }
        foreach($menu_items as $each) 
        {
            $arrX[$each['name']] = (int)$each['quantity'];
            $arrY[$each['name']] = (float)$each['total_price'];
        }
        return $this->successResponse([
            'arrX' => $arrX,
            'arrY' => $arrY,
            'day' => $today,
        ]);
        // return view('admin.statistic.statistic_day', [
        //     'arrX' => $arrX,
        //     'arrY' => $arrY,
        //     'day' => $today,
        //     'exists' => $exists,
        // ]);
        // dd(1);
    }
    public function statistic_month_i() 
    {
        return view('admin.statistic.statistic_month');
    }
    // public function statistic_month(Request $request)
    // {
    //     // dd($request->all());
    //     // $date = $request->all() ? (string)$request->all() : date('Y-m');
    //     $date = $request->input('date_input', date('Y-m'));
    //     // dd($date);
    //     $start_date = date('Y-m-d', strtotime("$date-01"));
    //     $end_date = date('Y-m-t', strtotime("$date-01"));
    //     // dd($start_day, $end_day);
    //     // $end_day = $request->all() ? $request->all() : 1;
    //     $menu_items = MenuItem::query()->pluck('name','id');
    //     $invoices = Invoice::selectRaw('menu_items.id as masanpham, menu_items.name as tensanpham, date_format(invoices.created_at, "%e-%m") as ngaytao,sum(invoice_details.quantity) as soluong')
    //                     ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
    //                     ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
    //                     // ->whereYear('invoices.created_at', $year)
    //                     ->whereBetween('invoices.created_at',[$start_date,$end_date])
    //                     // ->where('invoices.created_at', 'LIKE', '2024-%')
    //                     ->groupBy('masanpham', 'tensanpham', 'ngaytao')
    //                     ->get();

    //     $arr = [];
    //     $arr2 = [];
    //     foreach($menu_items as $id => $name)
    //     {
    //         $arr[$id] = [
    //             'name' => $name,
    //             'y' => 0,
    //             'drilldown' => $id,
    //         ];
    //     }
    //     foreach($invoices as $each)
    //     {
    //         $menu_item_id = $each['masanpham'];
    //         if(empty($arr[$menu_item_id]))
    //         {
    //             $arr[$menu_item_id] = [
    //                 'name' => $each['tensanpham'],
    //                 'y' => (int)$each['soluong'],
    //                 'drilldown' => (int)$each['masanpham'],
    //             ];
    //         }else{
    //             $arr[$menu_item_id]['y'] += (int)$each['soluong'];
    //         }
    //     }
    //     //key = menu_item_id
    //     $start_day = date('j', strtotime($start_date));
    //     $end_day = date('t', strtotime($end_date));
    //     $month = date('m', strtotime($date));
    //     foreach($arr as $menu_item_id => $each)
    //     {
    //         $arr2[$menu_item_id] = [
    //             'name' => $each['name'],
    //             'id' => $menu_item_id,
    //         ];
    //         $arr2[$menu_item_id]['data'] = [];
    //         for($i = $start_day; $i <= $end_day; $i++)
    //         {   
    //             $key = $i . '-' . $month;
    //             $arr2[$menu_item_id]['data'][$key] = [
    //                 $key,
    //                 0                    
    //             ];
    //         }
    //     }

    //     foreach($invoices as $each)
    //     {
    //         $menu_item_id = $each['masanpham'];
    //         $key = $each['ngaytao'];
    //         $arr2[$menu_item_id]['data'][$key] = [
    //             $key,
    //             (int)$each['soluong'],
    //         ];
    //     }

    //     return $this->successResponse([
    //         'arr1' => $arr,
    //         'arr2' => $arr2,
    //     ]);
    // }
    public function statistic_month(Request $request)
    {
        $date = $request->input('date_input', date('Y-m'));
        $start_date = date('Y-m-d', strtotime("$date-01"));
        $end_date = date('Y-m-t', strtotime("$date-01"));

        $menu_items = MenuItem::query()->pluck('name', 'id');

        $invoices = Invoice::selectRaw('menu_items.id as masanpham, menu_items.name as tensanpham, date_format(invoices.created_at, "%e-%m") as ngaytao,sum(invoice_details.quantity) as soluong')
            ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('menu_items', 'invoice_details.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('invoices.created_at', [$start_date, $end_date])
            ->groupBy('masanpham', 'tensanpham', 'ngaytao')
            ->get();

        $arr = [];
        $arr2 = [];

        foreach ($menu_items as $id => $name) {
            $arr[$id] = [
                'name' => $name,
                'y' => 0,
                'drilldown' => $id,
            ];
            $arr2[$id] = [
                'name' => $name,
                'id' => $id,
                'data' => [],
            ];
        }

        $start_day = date('j', strtotime($start_date));
        $end_day = date('t', strtotime($end_date));
        $month = date('m', strtotime($date));

        foreach ($arr2 as $menu_item_id => &$item) {
            for ($i = $start_day; $i <= $end_day; $i++) {
                $key = $i . '-' . $month;
                $item['data'][$key] = [$key, 0];
            }
        }

        foreach ($invoices as $invoice) {
            $menu_item_id = $invoice['masanpham'];
            $key = $invoice['ngaytao'];
            $arr[$menu_item_id]['y'] += (int)$invoice['soluong'];
            $arr2[$menu_item_id]['data'][$key] = [$key, (int)$invoice['soluong']];
        }

        return $this->successResponse([
            'arr1' => array_values($arr),
            'arr2' => array_values($arr2),
        ]);
    }

}
