<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Table;
use Illuminate\Http\Request;

class InvoiceApiController extends Controller
{
    use ResponseTrait;
    private object $model;

    // public function __construct()
    // {
    //     $this->model = Invoice::query();
    // }
    public function index()
    {
        $table_invoice_id = Table::query()
                                    ->where('invoice_id', '<>', 0)
                                    ->pluck('invoice_id')->toArray();
        $invoices = Invoice::with(['details' => function($query) {
            $query->select('invoice_id', 'menu_item_id', 'quantity');
        }, 'details.menuItems' => function($query) {
            $query->select('id', 'name','price');
        }])
        ->whereIn('id', $table_invoice_id)
        ->get()
        ->toArray();
        $message = 'Get api thanh cong';
        return $this->successResponse($invoices,$message);
    }
    
}
