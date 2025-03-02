<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class PositionController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $positions = Position::query()
                    ->where('salary', '<>', 0)
                    ->get();
        return view('admin.position.index', [
            'positions' => $positions,
        ]);
    }

    public function create()
    {
        return view('admin.position.create');
    }

    public function store(Request $request)
    {
        $count_pos = Position::selectRaw('count(id) as count')->value('count');
        if($count_pos == 5)
        {
            return redirect()->back()->with('error', 'Tối đa chỉ được 4 chức vụ!!!');
        }
        $pos_name = Str::title($request->position_name);
        $check_null = Position::query()
                ->where('name', $request->position_name)
                ->first();
        if(!is_null($check_null))
        {
            return redirect()->back()->with('error', 'Chức vụ đã tồn tại trong hệ thống');
        }

        Position::create([
            'name' => $pos_name,
            'salary' => $request->price * 1000,
        ]);

        return redirect()->route('admin.positions.index')->with('success', 'Thanh cong roi nhe');
    }

    public function edit($pos_id)
    {
        $position = Position::query()
                    ->where('id', $pos_id)
                    ->first();
        return view('admin.position.edit', [
            'position' => $position,
        ]);
    }

    public function update(Request $request)
    {
        Position::query()
                ->where('id', $request->pos_id)
                ->update([
                    'name' => $request->pos_name,
                    'salary' => $request->salary * 1000,
                ]);

        return redirect()->route('admin.positions.index')->with('success', 'Cap nhat thanh cong roi nhe');
    }

    public function destroy(Request $request)
    {
        $pos_id = $request->pos_id;
        $check = User::query()
                ->where('role', $pos_id)
                ->exists();
        if($check == true)
        {
            return $this->errorResponse('Hãy đảm bảo không còn nhân viên nào thuộc chức vụ này! Hãy thử lại sau');
        }
        Position::destroy($request->pos_id);
        return $this->successResponse([
            'pos_id' => $pos_id,
        ], 'Xoá chức vụ thành công!');
    }
}
