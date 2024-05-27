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
        //ORM (Object-Relational Mapping)
        $invoices = Invoice::with(['details' => function($query) {
            $query->select('invoice_id', 'menu_item_id', 'quantity');
        }, 'details.menuItems' => function($query) {
            $query->select('id', 'name','price');
        }])
        // ->select('id','customer_payment', 'remaining_money')
        ->whereIn('id', $table_invoice_id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->toArray();
        $table_names = getAndCacheTableName();

        // dd($invoices);
        $merged_array = [];
        $count = 0;
        //Nếu không dùng count mà dùng $table_id thì nó sẽ biến thành một object có key => value
        $session_invoices = session()->get('invoice');
        // dd(array_reverse($session_invoices));
        if(!is_null($session_invoices))
        {
            $session_invoices_reverse = array_reverse($session_invoices);
            foreach($session_invoices_reverse as $item)
            {
                $customer_payment = number_format($item['customer_payment'], 0, ',', '.');
                $remaining_money = number_format($item['remaining_money'], 0, ',', '.');
                $table_id = $item['table_id'];

                if($item['remaining_money'] < 0 || $item['customer_payment'] == null)
                {
                    $customer_payment = 'Không';
                    $remaining_money = 'Không';
                }
                $merged_array[$count] = [
                    'table_id' => $table_id,
                    'total_price' => $item['total_price'],
                    // 'created_at' => $item['created_at'],
                    'created_at' => date('d-m-Y', strtotime($item['created_at'])),
                    'checkin_time' => $item['checkin_time'],
                    'checkout_time' => $item['checkout_time'],
                    'is_paid' => 0,
                    'customer_payment' => $customer_payment,
                    'remaining_money' => $remaining_money,
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
            $customer_payment = number_format($item['customer_payment'], 0, ',', '.');
            $remaining_money = number_format($item['remaining_money'], 0, ',', '.');
            if($item['remaining_money'] < 0 || $item['customer_payment'] == null)
            {
                $customer_payment = 'Không';
                $remaining_money = 'Không';
            }
            $merged_array[$count]= [
                'table_id' => $table_id,
                'total_price' => $item['total_price'],
                'created_at' => date('d-m-Y', strtotime($item['created_at'])),
                // 'created_at' => $item['created_at'],
                'checkin_time' => $item['checkin_time'],
                'checkout_time' => $item['checkout_time'],
                'is_paid' => 1,
                'customer_payment' => $customer_payment,
                'remaining_money' => $remaining_money,
                // 'customer_payment' => $item['customer_payment'],
                // 'remaining_money' => $item['remaining_money'],
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
