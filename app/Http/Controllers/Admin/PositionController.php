<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
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
        $check = Position::query()  
                ->where('name', $request->position_name)
                ->first();
        if(!is_null($check))
        {
            return redirect()->back()->with('error', 'Mon da ton tai trong he thong');
        }

        Position::create([
            'name' => $request->position_name,
            'salary' => $request->price * 1000,
        ]);

        return redirect()->route('admin.positions.index')->with('success', 'Thanh cong roi nhe');
    }

    public function edit($pos_id)
    {
        $position = Position::query()
                    ->where('id', $pos_id)
                    ->first();
        // dd($position);
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
        Position::destroy($request->pos_id);
        return redirect()->back();
    }
}
