<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class StatisticController extends Controller
{
    use ResponseTrait;
    public function statistic_day(){
        //  $today =  date('Y-m-d');
         $today = date('2024-04-09');
        // $invoice_details = DB::table('invoices')
        //                 ->select('id')
        //                 ->get();
        // $invoices = Invoice::with([
        //     //eager loading
        //     'details' => function($query){
        //         $query->select('invoice_id','menu_item_id', 'quantity');
        //     },
        //     'details.menuItems' => function($query) {
        //         $query->select('id','name','price');
        //     }
        // ])
        // ->get();
        $menu_items = Invoice::selectRaw('year(created_at) as year, month(created_at) as month, day(created_at) as day,menu_items.name,sum(invoice_details.quantity) as quantity')
                ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
                ->join('menu_items','invoice_details.menu_item_id','=','menu_items.id')
                ->whereDate('created_at', '2024-04-09')
                ->groupBy('year', 'month', 'day','menu_items.name')
                ->get();
        // dd($menu_items);
        // if(isNull($menu_items)) {
        //     dd('Ngày nay đã bán được gì đâu mà thống kê');
        // }
        // $test = Invoice::selectRaw('HOUR(created_at) as HOUR, YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, sum(invoice_details.quantity) as quantity')
        //         ->join('invoice_details','invoices.id','=','invoice_details.invoice_id')
        //         ->groupBy('year', 'month', 'day', 'HOUR')
        //         ->get();

        // echo json_encode((object)$test);
        // dd($test);
        // echo json_encode($menu_items);
        // $datas = json_decode($menu_items);
        $arrX = [];
        $arrY = [];
        foreach($menu_items as $each) 
        {
            $arrX[] = $each['name'];
        }
        $jsonArrX = json_encode($arrX);
        // dd($arrX);
        return view('admin.statistic.statistic_day', [
            'arrX' => $jsonArrX,
        ]);        
    }
}
