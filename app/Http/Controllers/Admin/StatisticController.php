<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function statistic_day(){
         $today =  date('Y-m-d');
        // $invoice_details = DB::table('invoices')
        //                 ->select('id')
        //                 ->get();
        $invoices = Invoice::with([
            //eager loading
            'details' => function($query){
                $query->select('invoice_id','menu_item_id', 'quantity');
            },
            'details.menuItems' => function($query) {
                $query->select('id','name','price');
            }
        ])
        ->get();
        foreach($invoices as $each) {
            dd($each);
        }
        // dd($invoices->toArray());

    }
}
