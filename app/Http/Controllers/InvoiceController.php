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
            $names = $request->name;
            $quantity = $request->quantity;
            
            $items = [];
            // dd($names);
            foreach ($names as $id) {
                // $items[] = MenuItem::query()->where('id', $id)->get();
                $id = (int)$id;
                $item = MenuItem::query()->where('id', $id)->get();
                $items[] = $item;
            }
            dd($items);
            $item = MenuItem::query()->where('id', $id)->get();
            dd($item);
            // dd($request->all());
            return $this->successResponse($request);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
