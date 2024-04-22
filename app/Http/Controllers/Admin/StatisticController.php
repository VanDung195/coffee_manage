<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Invoice;
use App\Models\MenuItem;
use DateTime;
use Illuminate\Http\Request;


class StatisticController extends Controller
{
    use ResponseTrait;
    public function statistic_day_i(){
        return view('admin.statistic.statistic_day');
    }
    public function statistic_day(Request $request){
        // $today = date('Y-m-d');
        // $today = $request->all() ? $request->all() : date('Y-m-d');
        $day = $request->input('date_input', date('Y-m-d'));

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
                ->whereDate('created_at', $day)
                ->groupBy('year', 'month', 'day','menu_items.name')
                ->get();
        $arrX = [];
        $arrY = [];
        $total_price = 0.0;
        foreach($menu_items_name as $data) 
        {
            $arrX[$data] = 0;
            $arrY[$data] = 0;
        }
        foreach($menu_items as $each) 
        {
            $arrX[$each['name']] = (int)$each['quantity'];
            $arrY[$each['name']] = (float)$each['total_price'];
            $total_price += (float)$each['total_price'];
        }
        return $this->successResponse([
            'arrX' => $arrX,
            'arrY' => $arrY,
            'day' => $day,
            'total_price' => $total_price,
        ]);
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

        

        $invoices = Invoice::selectRaw('menu_items.id as masanpham, menu_items.name as tensanpham, date_format(invoices.created_at, "%e-%m") as ngaytao,sum(invoice_details.quantity) as soluong,
        sum(invoice_details.quantity*menu_items.price) as total_price')
            ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('menu_items', 'invoice_details.menu_item_id', '=', 'menu_items.id')
            ->whereBetween('invoices.created_at', [$start_date, $end_date])
            ->groupBy('masanpham', 'tensanpham', 'ngaytao')
            ->get();

        //arrChart1
        $arr = [];
        $arr2 = [];
        //arrChart2
        $arrChart2 = [];

        //Thay vì làm cách này sẽ tốn thêm 1 câu truy vấn thì làm cách dưới
        // $menu_items = MenuItem::query()->pluck('name', 'id');
        // foreach ($menu_items as $id => $name) {
        //     $arr[$id] = [
        //         'name' => $name,
        //         'y' => 0,
        //         'drilldown' => $id,
        //     ];
        //     $arr2[$id] = [
        //         'name' => $name,
        //         'id' => $id,
        //         'data' => [],
        //     ];
        // }

        $menu_items = getAndCacheMenuItems();
        foreach ($menu_items as $each) {
            $arr[$each['id']] = [
                'name' => $each['name'],
                'y' => 0,
                'drilldown' => $each['id'],
            ];
            $arr2[$each['id']] = [
                'name' => $each['name'],
                'id' => $each['id'],
                'data' => [],
            ];
        }

        // foreach($invoices as $each)
        // {
        //     $arr[$each['masanpham']] = [
        //         'name' => $each['tensanpham'],
        //         'y' => 0,
        //         'drilldown' => $each['masanpham'],
        //     ];
        //     $arr2[$each['masanpham']] = [
        //         'name' => $each['tensanpham'],
        //         'id' => $each['masanpham'],
        //         'data' => [],
        //     ];
        // }
        // dd($arr,$arr2);
        $start_day = date('j', strtotime($start_date));
        $end_day = date('t', strtotime($end_date));
        $month = date('m', strtotime($date));

        //set default values (0)
        foreach ($arr2 as $menu_item_id => $item) {
            for ($i = $start_day; $i <= $end_day; $i++) {
                $key = $i . '-' . $month;
                $item['data'][$key] = [$key, 0];
                //chart2
                $arrChart2[$key] = 0;
            }
        }
        $total_price = 0.0;
        foreach ($invoices as $invoice) {
            $menu_item_id = $invoice['masanpham'];
            $key = $invoice['ngaytao'];
            $arr[$menu_item_id]['y'] += (int)$invoice['soluong'];
            $arr2[$menu_item_id]['data'][$key] = [$key, (int)$invoice['soluong']];

            $arrChart2[$key] += (float)$invoice['total_price'];
            $total_price += (float)$invoice['total_price'];
        }
        // dd($arrChart2);

        return $this->successResponse([
            'arr1' => array_values($arr),
            'arr2' => array_values($arr2),
            'arrChart2' => $arrChart2,
            'total_price' => $total_price,
        ]);
    }
    public function statistic_year_i()
    {
        return view('admin.statistic.statistic_year');
    }
    public function statistic_year(Request $request)
    {
        $year = $request->input('year', date('Y'));
        // $year = date('Y');
        // dd($year);
        $start_month = 1;
        $end_month = 12;

        $invoices = Invoice::selectRaw('menu_items.id as masanpham, date_format(invoices.created_at, "%m") as thang,
                sum(invoice_details.quantity) as soluong, sum(invoices.total_price) as tongtien')
                ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                ->whereYear('invoices.created_at', $year)
                ->groupBy('masanpham', 'thang')
                ->get();
        // dd($sql);
        $arr1 = [];
        $arr2 = [];

        $arrChart2 = [];
        $menu_items = getAndCacheMenuItems();
        foreach($menu_items as $each) 
        {
            $arr1[$each['id']] = [
                'name' => $each['name'],
                'y' => 0,
                'drilldown' => $each['id'],
            ];
            $arr2[$each['id']] = [
                'name' => $each['name'],
                'id' => $each['id'],
                'data' => [],
            ];
        }
        // dd($arr2);
        foreach($arr2 as $items => $item)
        {
            for($i = $start_month; $i <= $end_month; $i++)
            {
                // dd($items, $item);
                //Gán vào mảng ở vị trí data, tạo một mảng mới có số $i và trong mảng đấy gán 2 giá trị
                // $item['data'][$i] = [$i, 0];
                $arr2[$item['id']]['data'][$i] = [$i,0];
                // dd($item['data'][$i]);

                $arrChart2[$i] = 0;
            }    
        }
        // dd($arr2);
        // dd($invoices->toArray());
        foreach($invoices as $invoice)
        {
            $menu_item_id = $invoice['masanpham'];
            $key = (int)$invoice['thang'];
            $arr1[$menu_item_id]['y'] += (int)$invoice['soluong'];
            // dd($arr1);
            $arr2[$menu_item_id]['data'][$key] = [$key, (int)$invoice['soluong']];

            $arrChart2[$key] += (int)$invoice['tongtien'];
        }
        // dd(array_values($arr1));
        return $this->successResponse([
            'arr1' => array_values($arr1),
            'arr2' => array_values($arr2),
            'arrChart2' => $arrChart2,
        ]);
    }
    public function statistic_date_range_i()
    {
        return view('admin.statistic.statistic_date_range');
    }
    public function statistic_date_range(Request $request)
    {
        $start_date = $request->input('start_date', '2024-3-1');
        $end_date = $request->input('end_date', date('Y-n-j'));
        $end_date_formatted = date('Y-n-j', strtotime($end_date . ' +1 day'));
        $number_of_days = (strtotime($end_date) - strtotime($start_date)) / (60*60*24);

        //arr chart 1
        $arrX = [];
        //arrchart drilldown
        $arr1 = [];
        $arr2 = [];
        $menu_items = getAndCacheMenuItems();
        //set default value (0)
        foreach ($menu_items as $each) {
            //chart1
            // $arrX[$each['name']] = 0;

            //drilldown chart
            $arr1[$each['id']] = [
                'name' => $each['name'],
                'y' => 0,
                'drilldown' => $each['id'] 
            ];

            $arr2[$each['id']] = [
                'name' => $each['name'],
                'id' => $each['id'],
                'data' => [],
            ];
            
        }  
        if($number_of_days <= 10)
        {
            $data = Invoice::selectRaw('year(created_at) as year, month(created_at) as month, 
                                    day(created_at) as day,menu_items.name,menu_items.id as id,sum(invoice_details.quantity) as quantity, 
                                    sum(invoice_details.quantity*menu_items.price) as total_price')
                        ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                        ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                        ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                        ->groupBy('year', 'month', 'day','menu_items.name', 'id')
                        ->get();
            foreach ($arr2 as $key => $value) {
                // $arrX[$each['name']] += (int)$each['quantity'];
                $current_date = $start_date;
                while($current_date <= $end_date)
                {   
                    // dd($current_date);
                    $arr2[$value['id']]['data'][$current_date] = [$current_date, 0];
                    $current_date = date('Y-n-j', strtotime($current_date . ' +1 day'));
                }
            }
            foreach($data as $each)
            {
                $menu_item_id = $each['id'];
                $key = $each['year'] . '-' . $each['month'] . '-' . $each['day'];
                $arr1[$menu_item_id]['y'] += (int)$each['quantity'];
                $arr2[$menu_item_id]['data'][$key] = [$key, (int)$each['quantity']];
            }
            // dd($arr2);
            return $this->successResponse([
                // 'arrX' => $arrX,
                'arr1' => $arr1,
                'arr2' => $arr2,
            ]);
        }

        //có thể đổi sang month(invoices.created_at) để không có số 0 ở đằng trước 
        $data = Invoice::selectRaw('menu_items.id as id, date_format(invoices.created_at, "%m-%Y") as month,
                sum(invoice_details.quantity) as quantity, sum(invoices.total_price) as total_price')
                ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                ->groupBy('id', 'month')
                ->get();

        $start_date_timestamp = strtotime($start_date);
        $end_date_timestamp = strtotime($end_date);

        $start_month = new DateTime(date('Y-m-d', $start_date_timestamp));
        $start_month = $start_month->format('m-Y');

        $end_month = new DateTime(date('Y-m-d', $end_date_timestamp));
        $end_month = $end_month->format('m-Y');

        // dd($end_month);
        foreach ($arr2 as $key => $value) {
            $current_month = $start_month;
            while($current_month <= $end_month)
            {
                $arr2[$value['id']]['data'][$current_month] = [$current_month, 0];
                $current_month = (new DateTime("01-$current_month"))->modify('+1 month')->format('m-Y');
                echo $current_month . '  ';
            }
            dd();
        }
        foreach($data as $each)
        {
            $menu_item_id = $each['id'];
            $key = $each['month'];
            $arr1[$menu_item_id]['y'] += (int)$each['quantity'];
            $arr2[$menu_item_id]['data'][$key] = [$key, (int)$each['quantity']];
        }
        return $this->successResponse([
            'arr1' => $arr1,
            'arr2' => $arr2,
        ]);
    }
}   
