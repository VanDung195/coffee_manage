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
        $appends = [];
        if (!is_null($selected_sort_total_price) && $selected_sort_total_price != 'none' && in_array($selected_sort_total_price, ['asc', 'desc'])) {
            $query->orderBy('total_price', $selected_sort_total_price);
            $appends['sort_total_price'] = $selected_sort_total_price;
        } elseif (!is_null($selected_sort_date) && $selected_sort_date != 'none' && in_array($selected_sort_date, ['asc', 'desc'])) {
            $query->orderBy('created_at', $selected_sort_date);
            $appends['sort_date'] = $selected_sort_date;
        } else {
            $query->orderBy('id', 'desc');
        }

        if(!is_null($search) && is_numeric($search))
        {
            $query->where('id', $search);
        }
        if(!is_null($search) && !is_numeric($search))
        {
            $query->whereHas('users', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        }
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
