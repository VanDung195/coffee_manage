<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ResponseTrait;

    public function index() 
    {
        //query raw
        // $invoices = Invoice::select('invoices.id', 'invoices.created_at', 'invoices.user_id', 'tables.name as table_name', 'users.name as user_name')
        //                 ->join('tables', 'invoices.table_id', '=', 'tables.id')
        //                 ->join('users', 'invoices.user_id', '=', 'users.id')
        //                 ->with(['details' => function($query) {
        //                     $query->select('invoice_id', 'menu_item_id', 'quantity');
        //                 }, 'details.menuItems' => function($query) {
        //                     $query->select('id', 'name', 'price');
        //                 }])
        //                 ->orderBy('invoices.created_at', 'desc')
        //                 ->paginate(15);

        //eager loading
        $invoices = Invoice::with(['details' => function($query) {
                $query->select('invoice_id', 'menu_item_id', 'quantity');
            }, 'details.menuItems' => function($query) {
                $query->select('id', 'name','price');
            }, 'tables' => function($query) {
                $query->select('id', 'name');
            }, 'users' => function($query) {
                $query->select('id', 'name');
            },
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        // dd($invoices);
        // foreach ($invoices as $invoice) {
        //     dd($invoice);
        //     $details = $invoice->details;
        //     foreach($details as $detail)
        //     {
        //         dd($detail->menuItems);
        //     }
        // }
        return view('admin.invoice.index',[
            'invoices' => $invoices,
        ]);
    }
}
