<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreRequest;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ResponseTrait;
    public function store(Request $request): JsonResponse
    {
        try {
            
            return $this->successResponse(1);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
