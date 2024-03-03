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
            $all = $request->all();
            
            dd($all['table-id']);
            $quantity = $request->quantity;
            $invoice_detail = [];
            $all = $request->all();
            // dd((int)$names[1]);
            $array = [];
            foreach ($array['name'] as $index => $names) {
                # code...
            }
            
            // dd($names);
            // foreach ($names as $id) {
            //     // $items[] = MenuItem::query()->where('id', $id)->get();
            //     $id = (int)$id;
            //     $item = MenuItem::query()->where('id', $id)->get();
            //     $items[] = $item;
            // }
            // dd($items);
            // $item = MenuItem::query()->where('id', $id)->get();
            // dd($item);
            // dd($request->all());
            return $this->successResponse($names);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
