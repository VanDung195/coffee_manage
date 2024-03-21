<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceApiController extends Controller
{
    use ResponseTrait;
    private object $model;

    public function __construct()
    {
        $this->model = Invoice::query();
    }
    
}
