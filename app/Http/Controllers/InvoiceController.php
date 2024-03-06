<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreRequest;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\MenuItem;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $menuNames = MenuItem::query()->whereIn('id',$ItemsId)->pluck('name');
                // dd($menuNames, $menuItems);
                $menuItemsMap = $menuItems->keyBy('id')->toArray();
                dd($menuItemsMap);
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
                
                $now = Carbon::now('Asia/Bangkok');
                dd($now->format('H:i:s'));
                $invoice = Invoice::create([
                    'created_at' => $now->format('d-m-Y'),
                    'checkin_time' => $now->format('H:i:s'),
                    'checkout_time' => $now->format('H:i:s'),
                    'total_price' => $total_price,
                ]);
                $invoice_id = $invoice->id;
                foreach ($ItemsId as $index => $id) {
                    // dd($index,$id);
                    /*
                    0 // app\Http\Controllers\InvoiceController.php:47
                    "2" // app\Http\Controllers\InvoiceController.php:47*/
                    $quantity = $allData['quantity'][$index];

                    $price = isset($menuItemsMap[$id]['price']) ? $menuItemsMap[$id]['price'] : 0;
                    // dd((float)$price);

                    InvoiceDetail::create([

                    ]);

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
