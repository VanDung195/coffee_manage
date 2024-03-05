<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreRequest;
use App\Models\Invoice;
use App\Models\MenuItem;
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

                //Lấy thông tin chi tiết của sản phấm 
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
                return $this->successResponse();
            }


        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
