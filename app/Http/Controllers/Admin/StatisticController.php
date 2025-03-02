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
                // ->where('is_hidden', false)
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

        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $invoices = Invoice::selectRaw('menu_items.id as masanpham, menu_items.name as tensanpham,
                                date_format(invoices.created_at, "%e-%m") as ngaytao,
                                sum(invoice_details.quantity) as soluong')
            ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('menu_items', 'invoice_details.menu_item_id', '=', 'menu_items.id')
            // ->whereBetween('invoices.created_at', [$start_date, $end_date])
            ->where('is_hidden', false)
            ->whereMonth('invoices.created_at', '=', $month)
            ->whereYear('invoices.created_at', $year)
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
        //test
        // $menu_items = MenuItem::query()
        //                 ->where('is_hidden', false)
        //                 ->get();
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

            // $arrChart2[$key] += (float)$invoice['total_price'];
            // $total_price += (float)$invoice['total_price'];
        }
        // dd($arrChart2);
        $invoice_for_total_price = Invoice::selectRaw('date_format(invoices.created_at, "%e-%m") as day_month,
                        sum(invoices.total_price) as total_price')
                        ->whereMonth('invoices.created_at', '=', $month)
                        ->whereYear('invoices.created_at', $year)
                        ->groupBy('day_month')
                        ->get();
        foreach($invoice_for_total_price as $item)
        {
            $key = $item['day_month'];
            $arrChart2[$key] += (float)$item['total_price'];
            $total_price += (float)$item['total_price'];
        }
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
                sum(invoice_details.quantity) as soluong')
                ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                ->where('menu_items.is_hidden', false)
                ->whereYear('invoices.created_at', $year)
                ->groupBy('masanpham', 'thang')
                ->get();
        // dd($sql);
        $arr1 = [];
        $arr2 = [];

        $arrChart2 = [];
        $menu_items = getAndCacheMenuItems();
        // $menu_items = MenuItem::query()
        //                 ->where('is_hidden', false)
        //                 ->get();
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
        $total_price = 0.0;
        foreach($invoices as $invoice)
        {
            $menu_item_id = $invoice['masanpham'];
            $key = (int)$invoice['thang'];
            $arr1[$menu_item_id]['y'] += (int)$invoice['soluong'];
            $arr2[$menu_item_id]['data'][$key] = [$key, (int)$invoice['soluong']];

            // $arrChart2[$key] += (float)$invoice['tongtien'];
            // $total_price += (float)$invoice['tongtien'];
        }
        $invoice_for_total_price = Invoice::selectRaw('month(created_at) as month, sum(invoices.total_price) as total_price')
                        ->whereYear('invoices.created_at', $year)
                        ->groupBy('month')
                        ->get();
        foreach($invoice_for_total_price as $item)
        {
            $key = (int)$item['month'];
            $arrChart2[$key] += (float)$item['total_price'];
            $total_price += (float)$item['total_price'];

        }
        // dd($arrChart2);
        // dd(array_values($arr1));
        return $this->successResponse([
            'arr1' => array_values($arr1),
            'arr2' => array_values($arr2),
            'arrChart2' => $arrChart2,
            'total_price' => $total_price,
        ]);
    }
    public function statistic_date_range_i()
    {
        return view('admin.statistic.statistic_date_range');
    }
    public function statistic_date_range(Request $request)
    {
        $start_date = $request->input('start_date', '2024-03-15');
        $end_date = $request->input('end_date', date('Y-m-d'));
        $end_date_formatted = date('Y-m-d', strtotime($end_date . ' +1 day'));
        $number_of_days = (strtotime($end_date) - strtotime($start_date)) / (60*60*24);

        //arr chart 1
        $arrX = [];
        //arrchart drilldown
        $arr1 = [];
        $arr2 = [];

        //array line chart
        $arrLine = [];
        $menu_items = getAndCacheMenuItems();
        //set default value (0)
        foreach ($menu_items as $each) {
            //chart1

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
        $total_price = 0.0;
        if($number_of_days <= 100)
        {
            $data = Invoice::selectRaw('date_format(invoices.created_at, "%d-%m-%Y") as date,
                                         menu_items.name, menu_items.id as id,
                                        sum(invoice_details.quantity) as quantity')
                        ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                        ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                        ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                        ->where('menu_items.is_hidden', false)
                        ->groupBy('date', 'menu_items.id','menu_items.name', 'id')
                        ->get();

            foreach ($arr2 as $key => $value) {
                $current_date = $start_date;
                while($current_date <= $end_date)
                {
                    $current_date_formatted = date('d-m-Y', strtotime($current_date));
                    $arr2[$value['id']]['data'][$current_date_formatted] = [$current_date_formatted, 0];

                    $arrLine[$current_date_formatted] = 0;
                    $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
                }
            }
            foreach($data as $each)
            {
                $menu_item_id = $each['id'];
                $key = $each['date'];
                $arr1[$menu_item_id]['y'] += (int)$each['quantity'];
                $arr2[$menu_item_id]['data'][$key] = [$key, (int)$each['quantity']];
            }
            $invoice_for_total_price = Invoice::selectRaw('date_format(invoices.created_at, "%d-%m-%Y") as date,
                                                        sum(invoices.total_price) as total_price')
                                    ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                                    ->groupBy('date')
                                    ->get();
            foreach($invoice_for_total_price as $item)
            {
                $key = $item['date'];
                $arrLine[$key] += (float)$item['total_price'];
                $total_price += (float)$item['total_price'];
            }
            return $this->successResponse([
                'arr1' => $arr1,
                'arr2' => $arr2,
                'arrLine' => $arrLine,
                'total_price' => $total_price,
            ]);
        }

        $start_date_timestamp = strtotime($start_date);
        $end_date_timestamp = strtotime($end_date);
        $start_date = new DateTime(date('Y-m-d', $start_date_timestamp));
        $end_date = new DateTime(date('Y-m-d', $end_date_timestamp));

        //start if number of day <= 2000
        if($number_of_days <= 2000)
        {
            //có thể đổi sang month(invoices.created_at) để không có số 0 ở đằng trước
            $data = Invoice::selectRaw('menu_items.id as id, date_format(invoices.created_at, "%m-%Y") as month,
                                        sum(invoice_details.quantity) as quantity')
                            ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                            ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                            ->where('menu_items.is_hidden', false)
                            ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                            ->groupBy('id', 'month')
                            ->get();

            $start_month = $start_date->format('m-Y');
            $end_month = $end_date->format('m-Y');

            //Tối ưu lại hoặc clean code sau
            list($thang_start, $nam_start) = explode('-', $start_month);
            list($thang_end, $nam_end) = explode('-', $end_month);

            //convert to integer
            $thang_start = intval($thang_start);
            $nam_start = intval($nam_start);
            $thang_end = intval($thang_end);
            $nam_end = intval($nam_end);
            foreach($arr2 as $items => $item)
            {
                $thang_start_1 = $thang_start;
                $nam_start_1 = $nam_start;
                $thang_end_1 = $thang_end;
                $nam_end_1 = $nam_end;
                while($nam_start_1 < $nam_end_1 || ($nam_start_1 === $nam_end_1 && $thang_start_1 <= $thang_end_1))
                {
                    $formatted_thang = ($thang_start_1 < 10) ? "0$thang_start_1" : $thang_start_1;
                    $key = $formatted_thang . '-' . $nam_start_1;
                    $arr2[$item['id']]['data'][$key] = [$key,0];
                    $arrLine[$key] = 0;
                    $thang_start_1++;
                    if($thang_start_1 > 12)
                    {
                        $thang_start_1 = 1;
                        $nam_start_1++;
                    }
                }
            }
            foreach($data as $each)
            {
                $menu_item_id = $each['id'];
                $key = $each['month'];
                $arr1[$menu_item_id]['y'] += (int)$each['quantity'];
                $arr2[$menu_item_id]['data'][$key] = [$key, (int)$each['quantity']];
            }
            $invoice_for_total_price = Invoice::selectRaw('date_format(invoices.created_at, "%m-%Y") as day_month,
                                                        sum(invoices.total_price) as total_price')
                                    ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                                    ->groupBy('day_month')
                                    ->get();
            foreach($invoice_for_total_price as $item)
            {
                $key = $item['day_month'];
                $arrLine[$key] += (float)$item['total_price'];
                $total_price += (float)$item['total_price'];
            }

            return $this->successResponse([
                'arr1' => $arr1,
                'arr2' => $arr2,
                'arrLine' => $arrLine,
                'total_price' => $total_price,
            ]);
        }
        //end (number of day <= 2000)
        $data = Invoice::selectRaw('menu_items.id as id, date_format(invoices.created_at, "%Y") as year,
                                sum(invoice_details.quantity) as quantity')
                        ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                        ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                        ->where('menu_items.is_hidden', false)
                        ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                        ->groupBy('id', 'year')
                        ->get();
        $start_year = intval($start_date->format('Y'));
        $end_year = intval($end_date->format('Y'));

        foreach($arr2 as $items => $item)
        {
            $nam_start = $start_year;
            $nam_end = $end_year;
            for($i = $nam_start; $i <= $nam_end; $i++)
            {
                $key = strval($i);
                $arr2[$item['id']]['data'][$key] = [$key, 0];
                $arrLine[$key] = 0;
            }
        }
        foreach($data as $each)
        {
            $menu_item_id = $each['id'];
            $key = $each['year'];
            $arr1[$menu_item_id]['y'] += (int)$each['quantity'];
            $arr2[$menu_item_id]['data'][$key] = [$key, (int)$each['quantity']];
            $arrLine[$key] += (float)$each['total_price'];
            $total_price += (float)$each['total_price'];
        }
        $invoice_for_total_price = Invoice::selectRaw('date_format(invoices.created_at, "%Y") as year,
                                                        sum(invoices.total_price) as total_price')
                                    ->whereBetween('invoices.created_at', [$start_date, $end_date_formatted])
                                    ->groupBy('year')
                                    ->get();
        foreach($invoice_for_total_price as $item)
        {
            $key = $item['year'];
            $arrLine[$key] += (float)$item['total_price'];
            $total_price += (float)$item['total_price'];
        }
        return $this->successResponse([
            'arr1' => $arr1,
            'arr2' => $arr2,
            'arrLine' => $arrLine,
            'total_price' => $total_price,
        ]);
    }
}
