<?php

namespace App\Http\Controllers;

use App\Enums\TableStausEnum;
use App\Events\InvoicePlaced;
use App\Http\Requests\Invoice\StoreRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class InvoiceController extends Controller
{
    use ResponseTrait;
    public function store(Request $request): JsonResponse
    {
        if((int)$request->is_paid === 1 && $request->customer_payment == null)
        {
            return $this->errorResponse('Vui lòng nhập số tiền khách trả!!');
        }
        if((int)$request->is_paid === 0 && $request->customer_payment != null)
        {
            return $this->errorResponse('Vui lòng chuyển đổi trạng thái thanh toán thành thanh toán trước!!!!');
        }
        try {
            $tableId = $request->input('table_id');
            $table_name = Table::query()
                            ->where('id', $tableId)
                            ->value('name');

            $allData = $request->all();
            $ItemsId = $allData['id'];
            $user_name = user()->name;
            if(in_array('0', $ItemsId))
            {
                return $this->errorResponse('Không được để trống món!');
            }
            $menuItems = MenuItem::query()
                ->whereIn('id', $ItemsId)->get();
            $menuItemsMap = $menuItems->keyBy('id')->toArray();
            $total_price = 0;
            foreach ($ItemsId as $index => $id) {
                $quantity = $allData['quantity'][$index];
                $price = $menuItemsMap[$id]['price'];
                $total_price += $quantity * $price;
            }
            $now = Carbon::now('Asia/Bangkok');
            $customer_payment = $request->customer_payment * 1000;

            //khi người dùng nhập 100000 (100 nghìn đồng) thay vì 100 (cũng là 100 nghìn đồng)
            if($customer_payment > 1000000000 || $customer_payment < 0)
            {
                return $this->errorResponse();
            }

            $remaining_money = $customer_payment - $total_price;
            if($remaining_money < 0 && $customer_payment != null)
            {
                return $this->errorResponse();
            }

            $customer_payment_response = number_format($customer_payment, 0, ',', '.');
            $remaining_money_response = number_format($remaining_money, 0, ',', '.');
            if($customer_payment == null)
            {
                $customer_payment = null;
                $remaining_money = null;
                $customer_payment_response = 'Không';
                $remaining_money_response = 'Không';
            }
            if ((int)$request->is_paid == 1) {
                $invoice = Invoice::create([
                    'created_at' => $now->format('Y:m:d H:i:s'),
                    'checkin_time' => $now->format('H:i:s'),
                    'checkout_time' => $now->format('H:i:s'),
                    'total_price' => $total_price,
                    'customer_payment' => $customer_payment,
                    'remaining_money' => $remaining_money,
                    'table_id' => $tableId,
                ]);
                $invoice_id = $invoice->id;

                if($table_name != 'takeaway')
                {
                    Table::where('id', $tableId)->update([
                        'status' => TableStausEnum::getKey(0),
                        'invoice_id' => $invoice_id,
                    ]);
                }

                foreach ($ItemsId as $index => $id) {
                    $quantity = $allData['quantity'][$index];

                    $price = isset($menuItemsMap[$id]['price']) ? $menuItemsMap[$id]['price'] : 0;
                    $thanh_tien = $quantity * $price;

                    $item = MenuItem::query()
                        ->where('id', $id)->first();
                    InvoiceDetail::create([
                        'invoice_id' => $invoice_id,
                        'menu_item_id' => $id,
                        'quantity' => $quantity,
                    ]);
                    $invoice_details[] = [
                        'menu_item_id' => $id,
                        'name' => $item->name,
                        'quantity' => $quantity,
                        'price' => number_format($price, 0, ',', '.'),
                        'thanh_tien' => number_format($thanh_tien, 0, ',', '.'),
                    ];
                }

                $message = 'Tạo hoá đơn thành công!';
                return $this->successResponse([
                    'table_id' => $tableId,
                    'table_name' => $table_name,
                    'user_name' => $user_name,
                    'details' => $invoice_details,
                    'total_price' => number_format($total_price, 0, ',', '.'),
                    'created_at' => $now->format('d-m-Y'),
                    'checkin_time' => $now->format('H:i:s'),
                    'checkout_time' => $now->format('H:i:s'),
                    'customer_payment' => $customer_payment_response,
                    'remaining_money' => $remaining_money_response,
                    'is_paid' => (int)$request->is_paid,
                    'invoice_id' => $invoice_id,
                    'customer_payment_check' => true,
                ], $message);
            }
            foreach ($ItemsId as $index => $id) {
                $quantity = $allData['quantity'][$index];

                $price = isset($menuItemsMap[$id]['price']) ? $menuItemsMap[$id]['price'] : 0;
                $thanh_tien = $quantity * $price;

                $item = MenuItem::query()
                    ->where('id', $id)->first();

                $invoice_details[] = [
                    'menu_item_id' => $id,
                    'name' => $item->name,
                    'quantity' => $quantity,
                    'price' => number_format($price, 0, ',', '.'),
                    'thanh_tien' => number_format($thanh_tien, 0, ',', '.'),
                ];
            }
            if (!session()->has('invoice')) {
                session()->put('invoice', []);
            }

            $invoice = session()->get('invoice');
            $invoice[$tableId] = [
                'user_id' => user()->id,
                'table_id' => $tableId,
                'table_name' => $table_name,
                'details' => $invoice_details,
                'total_price' => $total_price,
                'created_at' => Carbon::now(),
                'checkin_time' => $now->format('H:i:s'),
                'checkout_time' => null,
                'customer_payment' => $customer_payment,
                'remaining_money' => $remaining_money,
                'is_paid' => $request->is_paid,
                'is_qr' => 0,
                'invoice_id' => -1,
            ];
            session()->put('invoice', $invoice);

            $message = 'Thanh cong roi nhe!';
            return $this->successResponse([
                'user_name' => $user_name,
                'table_id' => $tableId,
                'table_name' => $table_name,
                'details' => $invoice_details,
                'total_price' => $total_price,
                'created_at' => $now->format('d-m-Y'),
                'checkin_time' => $now->format('H:i:s'),
                'checkout_time' => 'Không',
                'customer_payment' => $customer_payment_response,
                'remaining_money' => $remaining_money_response,
                'is_paid' => $request->is_paid,
                'is_qr' => 0,
                'invoice_id' => -1,
                'customer_payment_check' => false,
            ], $message);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function store_qr(StoreRequest $request)
    {
        if((int)$request->is_paid === 2 && $request->customer_payment == null)
        {
            return $this->errorResponse('Vui long nhap so tien ma ban phai tra');
        }
        if((int)$request->is_paid === 0 && $request->customer_payment != null)
        {
            return $this->errorResponse('Vui long chuyen doi trang thai thanh toan sang thanh toan truoc! Cam on quy khach.');
        }
        try {
            $selected_available = [0, 2];
            if(!in_array($request->is_paid, $selected_available))
            {
                return $this->errorResponse();
            }

            $tableId = $request->input('table_id');
            $table_name = Table::query()
                        ->where('id', $tableId)
                        ->value('name');
            $allData = $request->all();
            $ItemsId = $allData['id'];
            $menuItems = MenuItem::query()
                ->whereIn('id', $ItemsId)->get();
            $menuNames = MenuItem::query()->whereIn('id', $ItemsId)->pluck('name');
            $menuItemsMap = $menuItems->keyBy('id')->toArray();
            $total_price = 0;
            foreach ($ItemsId as $index => $id) {
                $quantity = $allData['quantity'][$index];
                $price = $menuItemsMap[$id]['price'];
                $total_price += $quantity * $price;
            }
            $now = Carbon::now('Asia/Bangkok');
            $customer_payment = $request->customer_payment * 1000;

            if($customer_payment > 1000000000 || $customer_payment < 0)
            {
                return $this->errorResponse();
            }
            $remaining_money = $customer_payment - $total_price;
            if($remaining_money < 0 && $customer_payment != null)
            {
                return $this->errorResponse();
            }
            $customer_payment_response = number_format($customer_payment, 0, ',', '.');
            $remaining_money_response = number_format($remaining_money, 0, ',', '.');
            if($request->customer_payment == null)
            {
                $customer_payment = null;
                $remaining_money = null;
                $customer_payment_response = 'Không';
                $remaining_money_response = 'Không';
            }
            foreach ($ItemsId as $index => $id) {
                $quantity = $allData['quantity'][$index];

                $price = isset($menuItemsMap[$id]['price']) ? $menuItemsMap[$id]['price'] : 0;
                $thanh_tien = $quantity * $price;

                $item = MenuItem::query()
                    ->where('id', $id)->first();
                $invoice_details[] = [
                    'menu_item_id' => $id,
                    'name' => $item->name,
                    'quantity' => $quantity,
                    'price' => number_format($price, 0, ',', '.'),
                    'thanh_tien' => number_format($thanh_tien, 0, ',', '.'),
                ];
            }
            $now = Carbon::now('Asia/Bangkok');
            event(new InvoicePlaced(
                $tableId,
                $table_name,
                $invoice_details,
                (float)$total_price,
                $now->format('Y:m:d H:i:s'),
                $now->format('H:i:s'),
                null,
                $customer_payment_response,
                $remaining_money_response,
                $customer_payment,
                $remaining_money,
                $request->is_paid,
                1,
                -1,
            ));
            return $this->successResponse(1);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    //update remaining money
    public function update(Request $request)
    {
        $is_create = $request->is_create;
        $all_data = $request->all();
        $items_id = $all_data['id'];
        $menu_items = MenuItem::query()
                    ->whereIn('id', $items_id)
                    ->get();
        $menu_items_map = $menu_items->keyBy('id')->toArray();
        $total_price = 0;
        foreach ($items_id as $index => $id) {
            $quantity = $all_data['quantity'][$index];
            $price = $menu_items_map[$id]['price'];
            $total_price += $quantity * $price;
        }

        $customer_payment = (float)$request->customer_payment * 1000;

        $remaining_money = $customer_payment - $total_price;
        if ($remaining_money < 0 && $customer_payment != null) {
            return $this->errorResponse($is_create);
        }
        if($customer_payment == null)
        {
            return $this->successResponse([
                'remaining_money' => 'NULL',
                'is_create' => $is_create,
            ]);
        }
        return $this->successResponse([
            'remaining_money' => $remaining_money,
            'is_create' => $is_create,
        ]);
    }

    //update table infonation
    public function invoice_table_update(Request $request)
    {
        $payment_status_old = $request->payment_status_old;
        $payment_status_new = $request->payment_status_new;

        $old_key = $request->from_table_id;
        $new_key = $request->to_table_id;
        if($old_key === $new_key)
        {
            return $this->errorResponse('Không được chọn bàn giống nhau!');
        }
        $old_key_name = Table::query()
                    ->where('id', $old_key)->value('name');
        $new_key_name = Table::query()
                ->where('id', $new_key)->value('name');
        $invoices = session()->get('invoice');
        $keys = [];
        if(!empty($invoices))
        {
            $keys = array_keys($invoices);
        }
        if(!empty($invoices) && (int)$payment_status_old == 0 || (int)$payment_status_old == 2 )
        {
            if(array_key_exists($new_key, $invoices))
            {
                $new_key_index = array_search($new_key, $keys);
                $old_key_index = array_search($old_key, $keys);
                $keys[$new_key_index] = $old_key;
                $keys[$old_key_index] = $new_key;
                $invoices = array_combine($keys, $invoices);

                $invoices[$old_key]['table_id'] = $old_key;
                $invoices[$old_key]['table_name'] = $old_key_name;
                $invoices[$new_key]['table_id'] = $new_key;
                $invoices[$new_key]['table_name'] = $new_key_name;
                session()->put('invoice', $invoices);
                return $this->successResponse([
                    'old_key' => $old_key,
                    'new_key' => $new_key,
                    'old_key_name' => $old_key_name,
                    'new_key_name' => $new_key_name,
                ],'Đổi bàn thành công!');
            }

            if(!array_key_exists($new_key, $invoices))
            {
                $invoice_id_new_key = Table::query()
                                        ->where('id', $new_key)
                                        ->value('invoice_id');

                Table::query()
                    ->where('id', $new_key)
                    ->update([
                        'status' => TableStausEnum::getKey(1),
                        'invoice_id' => 0,
                    ]);
                Table::query()
                    ->where('id', $old_key)
                    ->update([
                        'status' => TableStausEnum::getKey(0),
                        'invoice_id' => $invoice_id_new_key,
                    ]);
                Invoice::query()
                        ->where('id', $invoice_id_new_key)
                        ->update([
                            'table_id' => $old_key,
                        ]);
            }

            $keys[array_search($old_key, $keys)] = $new_key;
            $invoices = array_combine($keys, $invoices);
            $invoices[$new_key]['table_id'] = $new_key;
            $invoices[$new_key]['table_name'] = $new_key_name;
            session()->put('invoice', $invoices);

            return $this->successResponse([
                'old_key' => $old_key,
                'new_key' => $new_key,
                'old_key_name' => $old_key_name,
                'new_key_name' => $new_key_name,
            ], 'Thanh cong roi nhe');
        }
        if($payment_status_old == 1)
        {
            $table_invoice = Table::query()
                            ->whereIn('id', [$new_key, $old_key])
                            ->pluck('invoice_id', 'id');
            $invoice_id_new_key = $table_invoice[$new_key];
            $invoice_id_old_key = $table_invoice[$old_key];
            if($invoice_id_new_key != null && $invoice_id_old_key != null)
            {
                Table::query()
                    ->whereIn('id', [$new_key, $old_key])
                    ->update([
                        'invoice_id' => DB::raw("case when id = '$new_key' then $invoice_id_old_key else $invoice_id_new_key end"),
                    ]);

                DB::transaction(function() use ($invoice_id_new_key, $new_key, $invoice_id_old_key, $old_key) {
                    Invoice::query()
                        ->whereIn('id', [$invoice_id_new_key, $invoice_id_old_key])
                        ->update([
                            'table_id' => DB::raw("case when id = '$invoice_id_old_key' then '$new_key' else '$old_key' end"),
                        ]);
                });
                return $this->successResponse([
                    'old_key' => $old_key,
                    'new_key' => $new_key,
                    'old_key_name' => $old_key_name,
                    'new_key_name' => $new_key_name,
                ], 'Thanh cong roi nhe!');
            }

            //session
            if(!empty($invoices) && array_key_exists($new_key,$invoices))
            {
                $keys[array_search($new_key, $keys)] = $old_key;
                $invoices = array_combine($keys, $invoices);
                $invoices[$old_key]['table_id'] = $old_key;
                $invoices[$old_key]['table_name'] = $old_key_name;
                session()->put('invoice', $invoices);
            }
            Table::query()
                ->whereIn('id', [$new_key, $old_key])
                ->update([
                    'status' => DB::raw("case when id = '$new_key' then'" . TableStausEnum::getKey(0) . "'else'" . TableStausEnum::getKey(1) . "'end"),
                    'invoice_id' => DB::raw("case when id = '$new_key' then $invoice_id_old_key else 0 end"),
                ]);

            Invoice::query()
                ->where('id', $invoice_id_old_key)
                ->update([
                    'table_id' => $new_key,
                ]);

            return $this->successResponse([
                'old_key' => $old_key,
                'new_key' => $new_key,
                'old_key_name' => $old_key_name,
                'new_key_name' => $new_key_name,
            ], 'Thanh cong roi nhe!');
        }
    }

    public function redirect_success()
    {
        return view('qr.success');
    }
    public function generateInvoice(Request $request)
    {
        $invoice_id = (int)$request->invoice_id;
        $table_id = (int)$request->table_id;

        //
        $is_update_invoice = false;

        $now = Carbon::now('Asia/Bangkok');
        if($invoice_id < 0)
        {
            if(is_null($request->customer_payment))
            {
                return $this->errorResponse('Vui lòng nhập số tiền khách trả!!!');
            }
            $is_update_invoice = true;
            $customer_payment = $request->customer_payment * 1000;

            $data = session('invoice')[$table_id];
            $remaining_money = $customer_payment - $data['total_price'];
            // dd($remaining_money);
            if(!is_null($data['customer_payment']) && $data['is_qr'] === 1)
            {
                $customer_payment = $data['customer_payment'];
                $remaining_money = $data['remaining_money'];
            }
            if($remaining_money < 0 || is_null($customer_payment))
            {
                return $this->errorResponse('Error!!!');
            }
            $invoice = Invoice::create([
                'table_id' => (int)$data['table_id'],
                'total_price' => $data['total_price'],
                'checkin_time' => $data['checkin_time'],
                'checkout_time' => $now->format('H:i:s'),
                'customer_payment' => $customer_payment,
                'remaining_money' => $remaining_money,
                'created_at' => $data['created_at'],
            ]);
            $invoice_id = $invoice->id;
            $table_name = Table::query()
                            ->where('id', $table_id)
                            ->value('name');
            if($table_name != 'takeaway')
            {
                Table::where('id', $table_id)->update([
                    'status' => TableStausEnum::getKey(0),
                    'invoice_id' => $invoice_id,
                ]);
            }

            foreach ($data['details'] as $item) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice_id,
                    'menu_item_id' => (int)$item['menu_item_id'],
                    'quantity' => (int)$item['quantity'],
                ]);
            }
            $invoices = session()->get('invoice');
            if(isset($invoices[$table_id]))
            {
                unset($invoices[$table_id]);
                session()->put('invoice', $invoices);
            }
        }
        $data = Invoice::with(['details' => function($query) {
                    $query->select('invoice_id', 'menu_item_id', 'quantity');
                }, 'details.menuItems' => function($query) {
                    $query->select('id', 'name','price');
                }, 'tables' => function($query) {
                    $query->select('id', 'name');
                },
                ])
        ->where('id', $invoice_id)
        ->first()
        ->toArray();
        $user_name = User::query()
                    ->where('id', $data['user_id'])
                    ->value('name');
        $total_price_formatted = number_format($data['total_price'], 0, ',', '.');
        $customer_payment = number_format($data['customer_payment'], 0, ',', '.');
        $remaining_money = number_format($data['remaining_money'], 0, ',', '.');
        $invoice_formatted = [
            'user_name' => $user_name,
            'table_id' => $data['table_id'],
            'table_name' => $data['tables']['name'],
            'total_price' => $total_price_formatted,
            'created_at' => date('d-m-Y', strtotime($data['created_at'])),
            'checkin_time' => $data['checkin_time'],
            'checkout_time' => $data['checkout_time'],
            'customer_payment' => $customer_payment,
            'remaining_money' => $remaining_money,
            'details' => [],
            'invoice_id' => $data['id'],
            'customer_payment_check' => 1,
            'is_paid' => 1,
            'is_qr' => 0,
        ];
        foreach ($data['details'] as $item) {
            $invoice_formatted['details'][] = [
                'menu_item_id' => $item['menu_item_id'],
                'name' => $item['menu_items']['name'],
                'price' => number_format($item['menu_items']['price'], 0, ',', '.'),
                'quantity' => $item['quantity'],
                'thanh_tien' => number_format($item['menu_items']['price'] * $item['quantity'], 0, ',', '.'),
            ];
        }
        return $this->successResponse([
            'invoice' => $invoice_formatted,
            'is_update_invoice' => $is_update_invoice,
        ], 'Thanh cong roi nhe!!!');
    }


    public function putInvoice(Request $request)
    {
        $data = $request->all()['invoice'];
        // dd($data);
        $user_id = $data['user_id'];
        if($user_id === null)
        {
            $user_id = user()->id;
        }
        if (!session()->has('invoice')) {
            session()->put('invoice', []);
        }
        $invoice = session()->get('invoice');
        $customer_payment = $data['customer_payment'];
        $remaining_money = $data['remaining_money'];
        $customer_payment_response = number_format($customer_payment, 0, ',', '.');
        $remaining_money_response = number_format($remaining_money, 0, ',', '.');
        if($customer_payment == null)
        {
            $customer_payment = null;
            $remaining_money = null;
            $customer_payment_response = 'Không';
            $remaining_money_response = 'Không';
        }
        $customer_payment_check = false;
        $invoice[(int)$data['table_id']] = [
            'table_id' => (int)$data['table_id'],
            'table_name' => $data['table_name'],
            'details' => $data['details'],
            'total_price' => (float)$data['total_price'],
            'created_at' => $data['created_at'],
            'checkin_time' => $data['checkin_time'],
            'checkout_time' => $data['checkout_time'],
            'customer_payment' => $data['customer_payment'],
            'remaining_money' => $data['remaining_money'],
            'is_paid' => $data['is_paid'],
            'is_qr' => 1,
            'invoice_id' => -1,
            'user_id' => (int)$user_id,
        ];
        // dd($invoice);
        $user_name = User::query()
                    ->where('id', (int)$user_id)
                    ->value('name');
        if(!is_null($data['customer_payment']))
        {
            $customer_payment_check = true;
        }
        if(is_null($data['customer_payment']))
        {
            $customer_payment_response = 'Chưa';
            $remaining_money_response = 'Chưa';
        }
        session()->put('invoice', $invoice);
        return $this->successResponse([
            'user_name' => $user_name,
            'table_id' => $data['table_id'],
            'table_name' => $data['table_name'],
            'details' => $data['details'],
            'total_price' => number_format($data['total_price'], 0, ',', '.'),
            'created_at' => date('d-m-Y', strtotime($data['created_at'])),
            'checkin_time' => $data['checkin_time'],
            'checkout_time' => 'Chưa',
            'customer_payment' => $customer_payment_response,
            'remaining_money' => $remaining_money_response,
            'is_paid' => $data['is_paid'],
            'is_qr' => 1,
            'invoice_id' => -1,
            'user_id' => (int)$user_id,
            'customer_payment_check' => $customer_payment_check,
        ],'Co don hang moi!!!');
    }
}
