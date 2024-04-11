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
        // dd($request->all());
        $today = $request->all() ? $request->all() : date('Y-m-d');

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
}
