<?php

namespace App\Http\Controllers;

use App\Enums\TableStausEnum;
use App\Events\InvoicePlaced;
use App\Http\Requests\Invoice\StoreRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\MenuItem;
use App\Models\Table;
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
        try {
            $tableId = $request->input('table-id');
            $allData = $request->all();
            $ItemsId = $allData['id'];
            if(in_array('0', $ItemsId))
            {
                return $this->errorResponse('Không được để trống món!');
            }
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

                if($tableId != 'unknow' || $tableId != 'ta')
                {
                    Table::where('name', $tableId)->update([
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
                    // Table::where('name', $tableId)->update([
                    //     'status' => TableStausEnum::getKey(0),
                    //     'invoice_id' => $invoice_id,
                    // ]);
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
                    'details' => $invoice_details,
                    'total_price' => $total_price,
                    // 'created_at' => $now->format('Y:m:d H:i:s'),
                    'created_at' => $now->format('d-m-Y'),
                    'checkin_time' => $now->format('H:i:s'),
                    'checkout_time' => $now->format('H:i:s'),
                    'customer_payment' => $customer_payment_response,
                    'remaining_money' => $remaining_money_response,
                    'is_paid' => (int)$request->is_paid,
                ], $message);
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

            if (!session()->has('invoice')) {
                // session()->prepend('invoice', []);
                session()->put('invoice', []);
            }
            $invoice = session()->get('invoice');
            //thêm 1 trường hợp nữa đó là nếu trong csdl bàn đó bận rồi thì không cho đặt
            // if(!empty($invoice[$tableId]))
            // {
            //     return $this->errorResponse('Bàn đã được đặt, vui lòng kiểm tra lại!');
            //     dd('error');
            // }
            $invoice[$tableId] = [
                'table_id' => $tableId,
                'details' => $invoice_details,
                'total_price' => $total_price,
                'created_at' => $now->format('d-m-Y'),
                // 'created_at' => $now->format('Y:m:d H:i:s'),
                'checkin_time' => $now->format('H:i:s'),
                'checkout_time' => $now->format('H:i:s'),
                'customer_payment' => $customer_payment,
                'remaining_money' => $remaining_money,
                'is_paid' => $request->is_paid,
                'is_qr' => 0,
            ];
            session()->put('invoice', $invoice);
            // dd(session()->get('invoice'));

            $message = 'Thanh cong roi nhe!';
            return $this->successResponse([
                'table_id' => $tableId,
                'details' => $invoice_details,
                'total_price' => $total_price,
                // 'created_at' => $now->format('Y:m:d H:i:s'),
                'created_at' => $now->format('d-m-Y'),
                'checkin_time' => $now->format('H:i:s'),
                'checkout_time' => $now->format('H:i:s'),
                'customer_payment' => $customer_payment_response,
                'remaining_money' => $remaining_money_response,
                'is_paid' => $request->is_paid,
                'is_qr' => 0,
            ], $message);
        } catch (\Throwable $th) {
            dd($th);
        }


        dd();
        // Cái này là khi thu ngân lập hoá đơn và chọn trả trước
        try {
            $tableId = $request->input('table-id');
            // dd($tableId);
            $allData = $request->all();
            //Lấy danh sách các tên món có trong hoá đơn
            $ItemsId = $allData['id'];
            // dd($allData);
            //Lấy thông tin chi tiết của sản phấm (Hàm whereIn để tìm ra cái id của 1 mảng)
            $menuItems = MenuItem::query()
                ->whereIn('id', $ItemsId)->get();
            $menuNames = MenuItem::query()->whereIn('id', $ItemsId)->pluck('name');
            // dd($menuNames, $menuItems);
            $menuItemsMap = $menuItems->keyBy('id')->toArray();
            // dd($menuItemsMap);
            /*Ví dụ:
            array:2 [ // app\Http\Controllers\InvoiceController.php:45
                1 => array:4 [
                    "id" => 1
                    "menu_category_id" => 1
                    "name" => "Sinh tố dâu"
                    "price" => 25.5
                ]
                2 => array:4 [
                    "id" => 2
                    "menu_category_id" => 1
                    "name" => "Sinh tố lúa mạch"
                    "price" => 15.235
                ]
            ]
            */
            // dd($menuItemsMap);\
            // dd($tableId);
            // dd($index);
            //Bỏ cái số thứ tự trên này bị lỗi mới vcl
            $total_price = 0;
            foreach ($ItemsId as $index => $id) {
                // dd((int)$id);
                $quantity = $allData['quantity'][$index];
                $price = $menuItemsMap[$id]['price'];
                $total_price += $quantity * $price;
            }
            // dd($total_price);
            //cách cũ
            // check if current date is between two dates php
            // $currentDate = date('Y-m-d');
            // $endDate = $this->end_date->format('Y-m-d');
            // $startDate = $this->start_date->format('Y-m-d');
            // //echo $paymentDate; // echos today!

            // // if (($currentDate >= $this->end_date) && ($currentDate <= $this->start_date)){
            // //     return true;s
            // // }
            // // return false;
            // // dd($endDate);
            // // dd($this->$startDate);
            // return ($currentDate <= $endDate) || ($currentDate >= $startDate);
            // $date = date('Y-m-d');
            // $time = date('H:i:s');
            // dd($date, $time);
            // dd($total_price);
            // $dt = new DateTime();
            // dd($tableId);
            $now = Carbon::now('Asia/Bangkok');
            if ((int)$request->is_paid == 1) {
                $invoice = Invoice::create([
                    'created_at' => $now->format('Y:m:d H:i:s'),
                    'checkin_time' => $now->format('H:i:s'),
                    'checkout_time' => $now->format('H:i:s'),
                    'total_price' => $total_price,
                    'table_id' => $tableId,
                ]);

                $invoice_id = $invoice->id;
                // $invoice_id = 1;
                // dd($tableId);
                foreach ($ItemsId as $index => $id) {
                    // dd($index,$id);
                    /*
                    0 // app\Http\Controllers\InvoiceController.php:47
                    "2" // app\Http\Controllers\InvoiceController.php:47*/
                    $quantity = $allData['quantity'][$index];

                    $price = isset($menuItemsMap[$id]['price']) ? $menuItemsMap[$id]['price'] : 0;
                    // dd((float)$price);

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
                    // dd($quantity, $price);
                }

                // $indexQ = Table::query()->where('name',$tableId)->first();
                Table::where('name', $tableId)->update([
                    'status' => TableStausEnum::getKey(0),
                    'invoice_id' => $invoice_id,
                ]);
                // $index = $indexQ->stt;
                // dd($index);
                $message = 'Thanh cong roi nhe!';
                return $this->successResponse([
                    'table_id' => $tableId,
                    // 'index' => $index,
                    'details' => $invoice_details,
                    'total_price' => $total_price,
                    'created_at' => $now->format('Y:m:d H:i:s'),
                    'checkin_time' => $now->format('H:i:s'),
                    'checkout_time' => $now->format('H:i:s'),
                    'is_paid' => $request->is_paid,
                ], $message);
            }

            //is_paid ===  0 (chua thanh toan thi se them vao session de lo khach hang co huy hoa don)
            // if(!session()->has('invoice'))
            // {
            //     session()->put('invoice', []);
            // }

            // $invoice = session()->get('invoice');
            // if(array_key_exists($tableId, $invoice)){
            //     dd(1);
            // }

            // return $this->successResponse();

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
            $tableId = $request->input('table_id');
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
            if (!session()->has('invoice')) {
                session()->put('invoice', []);
            }
            $invoice = session()->get('invoice');

            $invoice[$tableId] = [
                'table_id' => $tableId,
                'details' => $invoice_details,
                'total_price' => $total_price,
                'created_at' => $now->format('Y:m:d H:i:s'),
                'checkin_time' => $now->format('H:i:s'),
                'checkout_time' => $now->format('H:i:s'),
                'customer_payment' => $customer_payment,
                'remaining_money' => $remaining_money,
                'is_paid' => $request->is_paid,
                'is_qr' => 1,
            ];
            session()->put('invoice', $invoice);
            event(new InvoicePlaced(
                $tableId,
                $invoice_details,
                $total_price,
                $now->format('Y:m:d H:i:s'),
                $now->format('H:i:s'),
                $now->format('H:i:s'),
                $request->is_paid
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
            return $this->errorResponse();
        }
        if($customer_payment == null)
        {
            return $this->successResponse(0);
        }
        return $this->successResponse($remaining_money);
    }

    //update table infonation
    public function invoice_table_update(Request $request)
    {
        // dd($request->all());
        $payment_status_old = $request->payment_status_old;
        $payment_status_new = $request->payment_status_new;
        $old_key = $request->from_table;
        $new_key = $request->to_table;

        $invoices = session()->get('invoice');
        $keys = [];
        if(!empty($invoices))
        {
            $keys = array_keys($invoices);
        }
        if($payment_status_old == 0 && !empty($invoices))
        {
            //Nếu muốn đổi 2 bàn đã tồn tại cho nhau và cả 2 đều chưa thanh toán trước (session)    
            if(array_key_exists($new_key, $invoices))
            {
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
                $invoices[$new_key]['table_id'] = $new_key;
                session()->put('invoice', $invoices);
                // dd('da ton tai array key');                
                return $this->successResponse([
                    'old_key' => $old_key,
                    'new_key' => $new_key,
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
                                        ->where('name', $new_key)
                                        // ->pluck('invoice_id');
                                        ->value('invoice_id');
                // dd($invoice_id_new_key[0]);
                Table::query()
                    ->where('name', $new_key)
                    ->update([
                        'status' => TableStausEnum::getKey(1),
                        'invoice_id' => 0,
                    ]);

                Table::query()
                    ->where('name', $old_key)
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
            session()->put('invoice', $invoices);
            
            return $this->successResponse([
                'old_key' => $old_key,
                'new_key' => $new_key,
            ], 'Thanh cong roi nhe');
        }
        if($payment_status_old == 1)
        {
            $table_invoice = Table::query()
                            ->whereIn('name', [$new_key, $old_key])
                            ->pluck('invoice_id', 'name');
            // dd($table_invoice);
            $invoice_id_new_key = $table_invoice[$new_key];
            $invoice_id_old_key = $table_invoice[$old_key];
            // dd($invoice_id_new_key, $invoice_id_old_key);

            //created
            if($invoice_id_new_key != null && $invoice_id_old_key != null)
            {
                // dd(1123);
                Table::query()
                    ->whereIn('name', [$new_key, $old_key])
                    ->update([
                        'invoice_id' => DB::raw("case when name = '$new_key' then $invoice_id_old_key else $invoice_id_new_key end"),
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
                ], 'Thanh cong roi nhe!');
            }

            //session
            if(!empty($invoices) && array_key_exists($new_key,$invoices))
            {
                dd('day la cho session');
                //chi update tren session thoi
                $keys[array_search($new_key, $keys)] = $old_key;
                $invoices = array_combine($keys, $invoices);
                $invoices[$old_key]['table_id'] = $old_key;
                session()->put('invoice', $invoices);
            }
            Table::query()
                ->whereIn('name', [$new_key, $old_key])
                ->update([
                    'status' => DB::raw("case when name = '$new_key' then'" . TableStausEnum::getKey(0) . "'else'" . TableStausEnum::getKey(1) . "'end"),
                    'invoice_id' => DB::raw("case when name = '$new_key' then $invoice_id_old_key else 0 end"),
                ]);

            Invoice::query()
                ->where('id', $invoice_id_old_key)
                ->update(['table_id' => $new_key]);

            return $this->successResponse([
                'old_key' => $old_key,
                'new_key' => $new_key,
            ], 'Thanh cong roi nhe!');
        }


    }

    public function redirect_success()
    {
        return view('qr.success');
    }
}
