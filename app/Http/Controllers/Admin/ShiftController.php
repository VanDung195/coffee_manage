<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    use ResponseTrait;

    public function index() 
    {
        $shifts = Shift::query()
                ->get();
        return view('admin.shift.index', [
            'shifts' => $shifts,
        ]);
    }

    public function getData(Request $request)
    {
        // dd($request->all());
        $id = (int)$request->id;
        $shift = Shift::query()
                    ->where('id', $id)
                    ->first();
        return $this->successResponse([
            'shift' => $shift,
        ]);
    }
}
