<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreRequest;
use App\Models\Invoice;
use App\Models\MenuItem;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ResponseTrait;
    public function store(Request $request): JsonResponse
    {
        try {
            if($request->is_paid == 1) {
                $tableId = $request->input('table-id');    
                $allData = $request->all();
                $invoice_detail = [];

                //Lấy danh sách các tên món có trong hoá đơn
                $ItemsId = $allData['id'];
                // dd($allData);
                //Lấy thông tin chi tiết của sản phấm (Hàm whereIn để tìm ra cái id của 1 mảng)
                $menuItems = MenuItem::query()
                            ->whereIn('id',$ItemsId)->get();

                $menuItemsMap = $menuItems->keyBy('id')->toArray();
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
                // dd($menuItemsMap);
                $total_price = 0;
                foreach($ItemsId as $index => $id) {
                    $quantity = $allData['quantity'][$index];
                    $price = $menuItemsMap[$id]['price'];

                    $total_price += $quantity * $price;
                }

                //cách cũ
                // check if current date is between two dates php
                // $currentDate = date('Y-m-d');

                // $endDate = $this->end_date->format('Y-m-d');
                // $startDate = $this->start_date->format('Y-m-d');
                // //echo $paymentDate; // echos today! 
                    
                // // if (($currentDate >= $this->end_date) && ($currentDate <= $this->start_date)){
                // //     return true;
                // // }

                // // return false;
                // // dd($endDate);
                // // dd($this->$startDate);
                // return ($currentDate <= $endDate) || ($currentDate >= $startDate);
                $date = date('Y-m-d');
                $time = date('H:i:s');
                dd($date, $time);
                // dd($total_price);
                // $dt = new DateTime();
                // $invoice = Invoice::create([
                //     'created_at' => date('d-m-Y H:i:s'),
                //     'checkin_time' => date('H:i:s'),
                //     'checkout_time' => date('H:i:s'),
                //     'total_price' => $total_price,
                // ]);

                foreach ($ItemsId as $index => $id) {
                    // dd($index,$id);
                    /*
                    0 // app\Http\Controllers\InvoiceController.php:47
                    "2" // app\Http\Controllers\InvoiceController.php:47*/
                    $quantity = $allData['quantity'][$index];

                    $price = isset($menuItemsMap[$id]['price']) ? $menuItemsMap[$id]['price'] : 0;
                    // dd((float)$price);

                    

                    // dd($quantity, $price);
                }
                return $this->successResponse(1);
            }
            return $this->successResponse();

        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
