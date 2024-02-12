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
            $id = (int)$request->id;
            $item = MenuItem::query()->where('id', 1)->get();
            return $this->successResponse($item);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
