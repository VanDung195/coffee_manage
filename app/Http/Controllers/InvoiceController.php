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

class InvoiceController extends Controller
{
    // : JsonResponse
    use ResponseTrait;
    // use NumberFormatter;

    public function store(Request $request): JsonResponse
    {
        // dd($request->all());
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
            if(in_array('0', $ItemsId))
            {
                return $this->errorResponse('Không được để trống món!');
            }
            $menuItems = MenuItem::query()
                ->whereIn('id', $ItemsId)->get();
            // $menuNames = MenuItem::query()->whereIn('id', $ItemsId)->pluck('name');
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
            // dd($customer_payment, $remaining_money);
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
                        'id' => $id,
                        'name' => $item->name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'thanh_tien' => $thanh_tien,
                    ];
                }

                $message = 'Tạo hoá đơn thành công!';
                return $this->successResponse([
                    'table_id' => $tableId,
                    'table_name' => $table_name,
                    'details' => $invoice_details,
                    'total_price' => $total_price,
                    // 'created_at' => $now->format('Y:m:d H:i:s'),
                    'created_at' => $now->format('d-m-Y'),
                    'checkin_time' => $now->format('H:i:s'),
                    'checkout_time' => $now->format('H:i:s'),
                    'customer_payment' => $customer_payment_response,
                    'remaining_money' => $remaining_money_response,
                    'is_paid' => (int)$request->is_paid,
                    'invoice_id' => $invoice_id,
                ], $message);
                // return response()->json([
                //     'success' => true,
                //     'data' => [
                //         'table_id' => $tableId,
                //         'table_name' => $table_name,
                //         'details' => $invoice_details,
                //         'total_price' => $total_price,
                //         // 'created_at' => $now->format('Y:m:d H:i:s'),
                //         'created_at' => $now->format('d-m-Y'),
                //         'checkin_time' => $now->format('H:i:s'),
                //         'checkout_time' => $now->format('H:i:s'),
                //         'customer_payment' => $customer_payment_response,
                //         'remaining_money' => $remaining_money_response,
                //         'is_paid' => (int)$request->is_paid,
                //         'invoice_id' => $invoice_id,
                //     ],
                //     'message' => $message,
                // ]);
            }
            // event(new InvoicePlaced($invoice_details));
            // broadcast(new InvoicePlaced($invoice_details));

            foreach ($ItemsId as $index => $id) {
                $quantity = $allData['quantity'][$index];

                $price = isset($menuItemsMap[$id]['price']) ? $menuItemsMap[$id]['price'] : 0;
                $thanh_tien = $quantity * $price;

                $item = MenuItem::query()
                    ->where('id', $id)->first();

                $invoice_details[] = [
                    'id' => $id,
                    'name' => $item->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'thanh_tien' => $thanh_tien,
                ];
            }
            // session()->flush();
            // dd('Before setting invoice', session()->all());

            // dd(1);
            if (!session()->has('invoice')) {
                session()->put('invoice', []);
            }

            $invoice = session()->get('invoice');
            // dd($invoice);

            //thêm 1 trường hợp nữa đó là nếu trong csdl bàn đó bận rồi thì không cho đặt
            // if(!empty($invoice[$tableId]))
            // {
            //     return $this->errorResponse('Bàn đã được đặt, vui lòng kiểm tra lại!');
            //     dd('error');
            // }
            $invoice[$tableId] = [
                'user_id' => user()->id,
                'table_id' => $tableId,
                'table_name' => $table_name,
                'details' => $invoice_details,
                'total_price' => $total_price,
                'created_at' => Carbon::now(),
                // 'created_at' => $now->format('Y:m:d H:i:s'),
                'checkin_time' => $now->format('H:i:s'),
                // 'checkout_time' => $now->format('H:i:s'),
                'checkout_time' => null,
                'customer_payment' => $customer_payment,
                'remaining_money' => $remaining_money,
                'is_paid' => $request->is_paid,
                'is_qr' => 0,
                'invoice_id' => -1,
            ];
            session()->put('invoice', $invoice);
            // dd(session()->get('invoice'));

            $message = 'Thanh cong roi nhe!';
            return $this->successResponse([
                'table_id' => $tableId,
                'table_name' => $table_name,
                'details' => $invoice_details,
                'total_price' => $total_price,
                // 'created_at' => $now->format('Y:m:d H:i:s'),
                'created_at' => $now->format('d-m-Y'),
                'checkin_time' => $now->format('H:i:s'),
                // 'checkout_time' => $now->format('H:i:s'),
                'checkout_time' => 'Không',
                'customer_payment' => $customer_payment_response,
                'remaining_money' => $remaining_money_response,
                'is_paid' => $request->is_paid,
                'is_qr' => 0,
                'invoice_id' => -1,
            ], $message);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    //tạo thêm 1 hàm store giành cho qr code để tránh có người xoá input hidden dẫn đến
    //chạy vào hàm store của thu ngân
    public function store_qr(StoreRequest $request)
    {
        // dd($request->all());
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
            // dd($customer_payment_response, $remaining_money_response);
            if($customer_payment == null)
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
                    'id' => $id,
                    'name' => $item->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'thanh_tien' => $thanh_tien,
                ];
            }
            // if (!session()->has('invoice')) {
            //     session()->put('invoice', []);
            // }
            // $invoice = session()->get('invoice');

            // $invoice[$tableId] = [
            //     'table_id' => $tableId,
            //     'table_name' => $table_name,
            //     'details' => $invoice_details,
            //     'total_price' => $total_price,
            //     'created_at' => $now->format('d-m-Y'),
            //     'checkin_time' => $now->format('H:i:s'),
            //     'checkout_time' => $now->format('H:i:s'),
            //     'customer_payment' => $customer_payment,
            //     'remaining_money' => $remaining_money,
            //     'is_paid' => $request->is_paid,
            //     'is_qr' => 1,
            // ];
            // session()->put('invoice', $invoice);
            // dd($customer_payment_response, $remaining_money_response);
            event(new InvoicePlaced(
                $tableId,
                $table_name,
                $invoice_details,
                (float)$total_price,
                $now->format('d-m-Y'),
                $now->format('H:i:s'),
                $now->format('H:i:s'),
                $customer_payment_response,
                $remaining_money_response,
                $customer_payment,
                $remaining_money,
                $request->is_paid,
                1,
            ));
            // return redirect()->to('http://coffee_manage.test/success');
            // return redirect()->action([TestController::class, 'success']);
            return $this->successResponse(1);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    //update remaining money
    public function update(Request $request)
    {
        // dd($request->all());
        $is_create = $request->is_create;
        $all_data = $request->all();
        $items_id = $all_data['id'];
        $menu_items = MenuItem::query()
                    ->whereIn('id', $items_id)
                    ->get();
        $menu_items_map = $menu_items->keyBy('id')->toArray();
        // dd($all_data, $menu_items, $menu_items_map);
        $total_price = 0;
        foreach ($items_id as $index => $id) {
            $quantity = $all_data['quantity'][$index];
            $price = $menu_items_map[$id]['price'];
            $total_price += $quantity * $price;
        }

        //test
        // $test = str_replace('.','', $request->total_price);
        // dd($test);


        /*
        // Chuỗi tiền tệ đã định dạng Cách 1
        $formattedTotal = "1.050.000";
        // Tạo một đối tượng NumberFormatter cho ngôn ngữ tiếng Việt
        $formatter = new NumberFormatter('vi-VN', NumberFormatter::DECIMAL);
        // Chuyển đổi chuỗi tiền tệ thành số
        $numericTotal = $formatter->parse($formattedTotal);   */

        /*
        // Chuỗi tiền tệ đã định dạng
        // Loại bỏ dấu chấm phân cách và dấu phân cách hàng nghìn
        $numericTotal = str_replace(['.', ','], '', $request->total_price);
        // Chuyển đổi chuỗi thành số
        $numericTotal = intval($numericTotal);
        */

        // $total_price = str_replace('.', '', $request->total_price);
        $customer_payment = (float)$request->customer_payment * 1000;
        // dd($customer_payment, $total_price);

        $remaining_money = $customer_payment - $total_price;
        if ($remaining_money < 0 && $customer_payment != null) {
            return $this->errorResponse($is_create);
        }
        if($customer_payment == null)
        {
            return $this->successResponse(0);
        }
        return $this->successResponse([
            'remaining_money' => $remaining_money,
            'is_create' => $is_create,
        ]);
    }

    //update table infonation
    public function invoice_table_update(Request $request)
    {
        // dd($request->all());
        $payment_status_old = $request->payment_status_old;
        $payment_status_new = $request->payment_status_new;

        //old key id
        $old_key = $request->from_table_id;
        $new_key = $request->to_table_id;

        //old key name
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
        // dd($invoices, $new_key);
        // dd($payment_status_old);
        // dd(array_key_exists($new_key, $invoices));
        if(!empty($invoices) && (int)$payment_status_old == 0 || (int)$payment_status_old == 2 )
        {
            //Nếu muốn đổi 2 bàn đã tồn tại cho nhau và cả 2 đều chưa thanh toán trước (session)    
            if(array_key_exists($new_key, $invoices))
            {
                // dd(1);
                //Đoạn này là đổi array_keys
                $new_key_index = array_search($new_key, $keys);
                $old_key_index = array_search($old_key, $keys);
                // dd($old_key_index, $new_key_index, $keys);
                $keys[$new_key_index] = $old_key;
                $keys[$old_key_index] = $new_key;
                //Gán array_keys vào value của invoices
                $invoices = array_combine($keys, $invoices);

                //Đổi array_values trong mảng invoices
                $invoices[$old_key]['table_id'] = $old_key;
                $invoices[$old_key]['table_name'] = $old_key_name;
                $invoices[$new_key]['table_id'] = $new_key;
                $invoices[$new_key]['table_name'] = $new_key_name;
                session()->put('invoice', $invoices);
                // dd('da ton tai array key');                
                return $this->successResponse([
                    'old_key' => $old_key,
                    'new_key' => $new_key,
                    'old_key_name' => $old_key_name,
                    'new_key_name' => $new_key_name,
                ],'thanh cong roi nhe');
            }
            
            // //test
            // if(!array_key_exists($new_key, $invoices))
            // {
            //     $invoice_id_new_key = Table::query()
            //                             ->where('name', $new_key)
            //                             ->get('invoice_id');
            //     dd($invoice_id_new_key);
            // }

            //nếu payment status old = 0 va payment status new = 1
            if(!array_key_exists($new_key, $invoices))
            {
                $invoice_id_new_key = Table::query()
                                        ->where('id', $new_key)
                                        // ->pluck('invoice_id');
                                        ->value('invoice_id');

                // dd($invoice_id_new_key[0]);
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

                // Table::query()
                //     ->whereIn('name', [$new_key, $old_key])
                //     ->update([
                //         'status' => DB::raw("CASE WHEN name = '$new_key' THEN '" . TableStausEnum::getKey(1) . "' ELSE '" . TableStausEnum::getKey(0) . "' END"),
                //         'invoice_id' => DB::raw("CASE WHEN name = '$new_key' THEN 0 ELSE $invoice_id_new_key END"),
                //     ]);

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
            // dd($table_invoice);
            $invoice_id_new_key = $table_invoice[$new_key];
            $invoice_id_old_key = $table_invoice[$old_key];
            // dd($invoice_id_new_key, $invoice_id_old_key);

            //created
            if($invoice_id_new_key != null && $invoice_id_old_key != null)
            {
                // dd(1123);
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
                // Invoice::query()
                //     ->where('id', $invoice_id_old_key)
                //     ->update(['table_id' => $new_key]);
                // Invoice::query()
                //     ->where('id', $invoice_id_new_key)
                //     ->update(['table_id' => $old_key]);

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
                // dd('day la cho session');
                //chi update tren session thoi
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

    // public function generatePDF()
    // {
    //     $invoice_s = session()->get('invoice');
    //     // dd($invoice[7]);
    //     $invoice = $invoice_s[7];

    //     $pdf = Pdf::loadView('test_print',[
    //         'invoice' => $invoice,
    //     ]);

    //     // $pdf->setPaper('A4', 'portrait')->setOptions([
    //     //     'isHtml5ParserEnabled' => true, 
    //     //     'isRemoteEnabled' => true,
    //     //     'defaultFont' => 'DejaVu Sans'
    //     // ]);
    //     //[, , width, bottom]
    //     $pdf->setPaper([0, 0, 400, 1000], 'portrait')->setOptions([
    //         'isHtml5ParserEnabled' => true, 
    //         'isRemoteEnabled' => true,
    //         'defaultFont' => 'DejaVu Sans'
    //     ]);

    //     return $pdf->stream('invoice.pdf');
    //     // dd($invoice);
    //     // return view('test_print', [
    //     //     'invoice' => $invoice,
    //     // ]);
    // }

    public function generateInvoice(Request $request)
    {
        //session thi invoice_id = -1 va chi co table_id
        // dd($request->all());
        $invoice_id = (int)$request->invoice_id;
        $table_id = (int)$request->table_id;

        //
        $is_update_invoice = false;
        //khi đã thanh toán và xuất hoá đơn rồi nhưng khách hàng vẫn muỗn xuất thêm 1 hoá đơn nữa thì
        // if($invoice_id > 0)  //lam sau
        // {
        //     dd(1);
        //     $data = Invoice::with(['details' => function($query) {
        //         $query->select('invoice_id', 'menu_item_id', 'quantity');
        //     }, 'details.menuItems' => function($query) {
        //         $query->select('id', 'name','price');
        //     }, 'tables' => function($query) {
        //         $query->select('id', 'name');
        //     },
        //     ])
        //     ->where('id', $invoice_id)
        //     ->get()
        //     ->toArray();
        //     $invoice = [];
        //     $invoice_id = $data['id'];
        //     $user_name = User::query()
        //                 ->where('id', $data['user_id'])
        //                 ->value('name');
        //     $table_name = Table::query()
        //                 ->where('id', $data['table_id'])
        //                 ->value('name');
        //     $total_price = $data['total_price'];
        //     $created_at = date('d-m-Y', strtotime($data['created_at']));
        //     $checkin_time = $data['checkin_time'];
        //     $checkout_time = $data['checkout_time'];
        //     $customer_payment = $data['customer_payment'];
        //     // foreach ($data['details'] as $item) {
        //     // }
        //     $table_id = $data['table_id'];
        //     $invoice = [
                
        //     ];
        // }
        $now = Carbon::now('Asia/Bangkok');
        if($invoice_id < 0)
        {
            $is_update_invoice = true;
            // dd($request->all());
            $customer_payment = $request->customer_payment;
            if(is_null($customer_payment)){
                return $this->errorResponse('Vui lòng nhập số tiền khách trả!!!');
            }
            $data = session('invoice')[$table_id];
            $remaining_money = ($customer_payment * 1000) - $data['total_price'];
            if($remaining_money < 0 || is_null($customer_payment))
            {
                return $this->errorResponse('Error!!!');
            }
            ///////////////lam cai nay nhe!!!!
            // $table_id = $data['table_id'];
            // $data['checkout_time'] = $now->format('H:i:s');
            // $data['remaining_money'] = $remaining_money;
            // session()->put('invoice', $data);
            // dd($data);
            $invoice = Invoice::create([
                'table_id' => (int)$data['table_id'],
                'total_price' => $data['total_price'],
                'checkin_time' => $data['checkin_time'],
                'checkout_time' => $now->format('H:i:s'),
                'customer_payment' => $customer_payment * 1000,
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
                    'menu_item_id' => (int)$item['id'],
                    'quantity' => (int)$item['quantity'],
                ]);
            }
            $invoices = session()->get('invoice');
            if(isset($invoices[$table_id]))
            {
                unset($invoices[$table_id]);
                session()->put('invoice', $invoices);
                // dd($invoices, $table_id);
            }

            // $invoices = session()->get('invoice');
            // if (isset($invoices[$table_id])) {
            //     $index = array_search($table_id, array_keys($invoices));
            //     if ($index !== false) {
            //         array_splice($invoices, $index, 1);
            //         session()->put('invoice', $invoices);
            //     }
            // }

            // dd($data['customer_payment']);
            // dd($invoice_session);
        }
        //chưa thanh toán
        // dd($invoice);
        // $table_id = $invoice->table_id;
        // $table_name = $invoice_table_name;
        // $total_price = $invoice->total_price;
        // $created_at = $invoice->created_at;
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
        ///đổi nó thành 1 mảng giống bên invoice api cho nó khoẻ nhé!!!!! 
        ///làm ngang cỡ 12 giờ thôi, học giải tích và tt HCM
        // dd($data);
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
            // 'created_at' => $data['created_at'],
            'created_at' => date('d-m-Y', strtotime($data['created_at'])),
            'checkin_time' => $data['checkin_time'],
            'checkout_time' => $data['checkout_time'],
            'customer_payment' => $customer_payment,
            'remaining_money' => $remaining_money,
            // 'details' => $data['details'],
            'details' => [],
            'invoice_id' => $data['id'],
            'customer_payment_check' => 1,
            'is_paid' => 1,
            'is_qr' => 0,
        ];
        //làm cái này để format cái price thành vnđ
        foreach ($data['details'] as $item) {
            $invoice_formatted['details'][] = [
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'thanh_tien' => number_format($item['menu_items']['price'] * $item['quantity'], 0, ',', '.'),
                'menu_items' => [
                    'id' => $item['menu_items']['id'],
                    'name' => $item['menu_items']['name'],
                    // 'price' => $item['menu_items']['price'],
                    'price' => number_format($item['menu_items']['price'], 0, ',', '.'),
                ],
            ];
        }
        // dd($invoice_formatted);

        return $this->successResponse([
            'invoice' => $invoice_formatted,
            'is_update_invoice' => $is_update_invoice,
        ], 'Thanh cong roi nhe!!!');
    }


    ///sửa lại định dạng datetime cho cái created_at ở hàm dưới
    public function putInvoice(Request $request)
    {
        // dd($request->all());
        $data = $request->all()['invoice'];

        if (!session()->has('invoice')) {
            session()->put('invoice', []);
        }
        $invoice = session()->get('invoice');
        //vì đã xử lý ở hàm store qr rồi nên ở đây không cần
        // $customer_payment = $request->customer_payment * 1000;
        // $remaining_money = $customer_payment - $total_price;
        // dd($data);
        $invoice[(int)$data['table_id']] = [
            'table_id' => (int)$data['table_id'],
            'table_name' => $data['table_name'],
            'details' => $data['details'],
            'total_price' => (float)$data['total_price'],
            'created_at' => $data['created_at'],
            'checkin_time' => $data['checkin_time'],
            'checkout_time' => $data['checkout_time'],
            'customer_payment' => (float)$data['customer_payment'],
            'remaining_money' => (float)$data['remaining_money'],
            'is_paid' => $data['is_paid'],
            'is_qr' => 1,
            'invoice_id' => -1,
        ];
        
        session()->put('invoice', $invoice);
        return 1;
    }
}
