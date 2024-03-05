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
            $tableId = $request->input('table-id');    
            $allData = $request->all();
            $invoice_detail = [];

            //Lấy danh sách các tên món có trong hoá đơn
            $ItemNames = $allData['name'];

            //Lấy thông tin chi tiết của sản phấm 
            $menuItems = MenuItem::query()
                        ->whereIn('id',$ItemNames)->get();

            $menuItemsMap = $menuItems->keyBy('name')->toArray();

            // dd($menuItemsMap);
            foreach ($ItemNames as $index => $name) {
                $quantity = $allData['quantity'][$index];

            }
            return $this->successResponse();
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
