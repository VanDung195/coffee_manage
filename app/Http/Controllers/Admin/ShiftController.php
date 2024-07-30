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
                ->where('time', '<>', 0)
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

    public function update(Request $request)
    {
        $id = $request->id;
        $time = (int)$request->time;
        $description = $request->description;

        if(!is_numeric($time))
        {
            return $this->errorResponse('Giờ phải là một số nguyên!!');
        }

        $shift = Shift::findOrFail($id);

        $shift->time = $time;
        $shift->description = $description;

        $shift->save();

        return $this->successResponse([
            'id' => $id,   
            'time' => $time,
            'description' => $description,
        ], 'Cập nhật ca thành công!!!');
    }
}
