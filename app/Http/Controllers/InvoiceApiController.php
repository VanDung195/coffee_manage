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
        ->orderBy('created_at', 'desc')
        ->get()
        ->toArray();
        $table_names = getAndCacheTableName();

        $merged_array = [];
        $count = 0;
        //Nếu không dùng count mà dùng $table_id thì nó sẽ biến thành một object có key => value
        $session_invoices = session()->get('invoice');
        if(!is_null($session_invoices))
        {
            foreach($session_invoices as $item)
            {
                $table_id = $item['table_id'];
                $merged_array[$count] = [
                    'table_id' => $table_id,
                    'total_price' => $item['total_price'],
                    'created_at' => $item['created_at'],
                    'checkin_time' => $item['checkin_time'],
                    'checkout_time' => $item['checkout_time'],
                    'is_paid' => 0,
                    'details' => []
                ];
                foreach($item['details'] as $each)
                {
                    $merged_array[$count]['details'][] = [
                        'menu_item_id' => (int)$each['id'],
                        'quantity' => (int)$each['quantity'],
                        'menu_items' => [
                            'id' => (int)$each['id'],
                            'name' => $each['name'],
                            'price' => $each['price'],
                        ],
                    ];
                }
                $count++;
            }
        }

        foreach($invoices as $item)
        {
            $table_id = $item['table_id'];
            $merged_array[$count]= [
                'table_id' => $table_id,
                'total_price' => $item['total_price'],
                'created_at' => $item['created_at'],
                'checkin_time' => $item['checkin_time'],
                'checkout_time' => $item['checkout_time'],
                'is_paid' => 1,
                'details' => $item['details']
            ];
            $count++;
        }

        $message = 'Get api thanh cong';
        // return $this->successResponse($invoices,$message);
        return $this->successResponse([
            // 'invoices' => $invoices,
            'invoices' => (array)$merged_array,
            'table_names' => $table_names,
        ]
        ,$message);
    }
    
}
