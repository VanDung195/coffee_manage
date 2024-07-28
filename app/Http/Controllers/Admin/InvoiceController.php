<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    use ResponseTrait;
    protected object $model;

    public function __construct()
    {
        $this->model = Invoice::query();
    }

    public function index(Request $request) 
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
        // $invoices = Invoice::with(['details' => function($query) {
        //         $query->select('invoice_id', 'menu_item_id', 'quantity');
        //     }, 'details.menuItems' => function($query) {
        //         $query->select('id', 'name','price');
        //     }, 'tables' => function($query) {
        //         $query->select('id', 'name');
        //     }, 'users' => function($query) {
        //         $query->select('id', 'name');
        //     },
        // ])
        // ->orderBy('created_at', 'desc')
        // ->paginate(15);
        // dd($invoices);
        // foreach ($invoices as $invoice) {
        //     dd($invoice);
        //     dd($invoice->details->menuItems);
        //     $details = $invoice->details;
        //     foreach($details as $detail)
        //     {
        //         dd($detail->menuItems);
        //     }
        // }
        $selected_sort_total_price = $request->sort_total_price;
        $selected_sort_date = $request->sort_date;
        $search = $request->search;
        
        $query = $this->model->newQuery()
                ->with(['details' => function($query) {
                    $query->select('invoice_id', 'menu_item_id', 'quantity');
                }, 'details.menuItems' => function($query) {
                    $query->select('id', 'name','price');
                }, 'tables' => function($query) {
                    $query->select('id', 'name');
                }, 'users' => function($query) {
                    $query->select('id', 'name');
                },
            ]);
            // ->orderBy('invoices.created_at', 'desc');

        // if(!is_null($selected_sort))
        // {
        //     if($selected_sort == 'asc' || $selected_sort == 'desc')
        //     {
        //         $query->orderBy('total_price', $selected_sort);
        //     }
        // }
        // if (!is_null($selected_sort) && in_array($selected_sort, ['asc', 'desc'])) {
        //     $query->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
        //           ->join('menu_items', 'invoice_details.menu_item_id', '=', 'menu_items.id')
        //           ->orderBy('invoices.total_price', $selected_sort)
        //           ->select('invoices.*'); 
        // }
        
        // if (!is_null($selected_sort_total_price) && in_array($selected_sort_total_price, ['asc', 'desc'])) {
        //     // Log::info('Applying sort order:', ['column' => 'total_price', 'order' => $selected_sort]);
        //     $query->orderBy('total_price', $selected_sort_total_price);
        // } else {
        //     $query->orderBy('created_at', 'desc');
        // }

        // if (!is_null($selected_sort_total_price) && in_array($selected_sort_total_price, ['asc', 'desc'])) {
        //     $query->orderBy('total_price', $selected_sort_total_price);
        // }else {
        //     $query->orderBy('created_at', 'desc');
        // }

        // if (!is_null($selected_sort_date) && in_array($selected_sort_date, ['asc', 'desc'])) {
        //     $query->orderBy('created_at', $selected_sort_date);
        // } else {
        //     $query->orderBy('created_at', 'desc');
        // }
        $appends = [];
        if (!is_null($selected_sort_total_price) && $selected_sort_total_price != 'none' && in_array($selected_sort_total_price, ['asc', 'desc'])) {
            $query->orderBy('total_price', $selected_sort_total_price);
            $appends['sort_total_price'] = $selected_sort_total_price;
        } elseif (!is_null($selected_sort_date) && $selected_sort_date != 'none' && in_array($selected_sort_date, ['asc', 'desc'])) {
            $query->orderBy('created_at', $selected_sort_date);
            $appends['sort_date'] = $selected_sort_date;
        } else {
            // $query->orderBy('created_at', 'desc');
            $query->orderBy('id', 'desc');
        }

        if(!is_null($search) && is_numeric($search))
        {
            $query->where('id', $search);
        }
        if(!is_null($search) && !is_numeric($search))
        {
            // $query->where('name', 'LIKE', "%{$search}%");
            $query->whereHas('users', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        }
        // $invoices = $query->paginate(15)->appends($request->all());
        $invoices = $query->paginate(500)->appends($appends);
        // dd($invoices);
        return view('admin.invoice.index',[
            'invoices' => $invoices,
            'selected_sort_total_price' => $selected_sort_total_price,
            'selected_sort_date' => $selected_sort_date,
            'search' => $search,
        ]);
    }
}
