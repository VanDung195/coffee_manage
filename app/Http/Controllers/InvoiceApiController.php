<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceApiController extends Controller
{
    use ResponseTrait;
    private object $model;

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
        }, 'tables' => function($query) {
            $query->select('id', 'name');
        },
        ])
        ->whereIn('id', $table_invoice_id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->toArray();
        $merged_array = [];
        $count = 0;
        //Nếu không dùng count mà dùng $table_id thì nó sẽ biến thành một object có key => value
        $session_invoices = session()->get('invoice');
        if(!is_null($session_invoices))
        {
            foreach($session_invoices as $item)
            {
                $user_name = User::query()
                            ->where('id', $item['user_id'])
                            ->value('name');
                $customer_payment_check = true;
                $customer_payment = number_format($item['customer_payment'], 0, ',', '.');
                $remaining_money = number_format($item['remaining_money'], 0, ',', '.');
                $table_id = $item['table_id'];

                if($item['remaining_money'] < 0 || $item['customer_payment'] == null)
                {
                    $customer_payment_check = false;
                    $customer_payment = 'Không';
                    $remaining_money = 'Không';
                }
                $merged_array[$count] = [
                    'user_name' => $user_name,
                    'table_id' => $table_id,
                    'table_name' => $item['table_name'],
                    'total_price' => $item['total_price'],
                    'created_at' => date('d-m-Y', strtotime($item['created_at'])),
                    'checkin_time' => $item['checkin_time'],
                    'checkout_time' => $item['checkout_time'] ? $item['checkout_time'] : 'Chưa',
                    'is_paid' => $item['is_paid'],
                    'customer_payment' => $customer_payment,
                    'remaining_money' => $remaining_money,
                    'details' => [],
                    'is_qr' => $item['is_qr'],
                    'invoice_id' => -1,
                    'customer_payment_check' => $customer_payment_check,
                ];
                foreach($item['details'] as $each)
                {
                    $merged_array[$count]['details'][] = [
                        'menu_item_id' => (int)$each['menu_item_id'],
                        'name' => $each['name'],
                        'price' => $each['price'],
                        'quantity' => (int)$each['quantity'],
                        'thanh_tien' => $each['thanh_tien'],
                    ];
                }
                $count++;
            }
        }
        foreach($invoices as $item)
        {
            $table_id = $item['table_id'];
            $customer_payment_check = true;
            $customer_payment = number_format($item['customer_payment'], 0, ',', '.');
            $remaining_money = number_format($item['remaining_money'], 0, ',', '.');
            $total_price = number_format($item['total_price'], 0, ',', '.');
            $user_name = User::query()
                        ->where('id', $item['user_id'])
                        ->value('name');
            if($item['remaining_money'] < 0 || $item['customer_payment'] == null)
            {
                $customer_payment_check = false;
                $customer_payment = 'Không';
                $remaining_money = 'Không';
            }
            $merged_array[$count]= [
                'user_name' => $user_name,
                'table_id' => $table_id,
                'table_name' => $item['tables']['name'],
                'total_price' => $total_price,
                'created_at' => date('d-m-Y', strtotime($item['created_at'])),
                'checkin_time' => $item['checkin_time'],
                'checkout_time' => $item['checkout_time'],
                'is_paid' => 1,
                'customer_payment' => $customer_payment,
                'remaining_money' => $remaining_money,
                'details' => [],
                'is_qr' => 0,
                'invoice_id' => $item['id'],
                'customer_payment_check' => $customer_payment_check,
            ];
            //để format cái tiền
            foreach ($item['details'] as $item) {
                $merged_array[$count]['details'][] = [
                    'menu_item_id' => $item['menu_item_id'],
                    'name' => $item['menu_items']['name'],
                    'price' => number_format($item['menu_items']['price'], 0, ',', '.'),
                    'quantity' => $item['quantity'],
                    'thanh_tien' => number_format($item['menu_items']['price'] * $item['quantity'], 0, ',', '.'),
                ];
            }
            $count++;
        }
        $message = 'Get api thanh cong';
        $table_names_available = getAndCacheAvailableTableNames();
        return $this->successResponse([
            'invoices' => (array)$merged_array,
            'table_names_available' => $table_names_available,
        ]
        ,$message);
    }

}
