<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $tables = Table::query()
                // ->orderBy('stt', 'asc')    
                ->orderByRaw("CASE
                    WHEN name REGEXP '^[A-Za-z]+' THEN CONCAT(LEFT(name, LENGTH(name) - LENGTH(SUBSTRING_INDEX(name, '_', -1))), LPAD(SUBSTRING_INDEX(name, '_', -1), 10, '0'))
                    ELSE name
                  END")
                ->get();

        return view('admin.table.index', [
            'tables' => $tables,
        ]);
    }
    public function create()
    {
        return view('admin.table.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $name = $request->name;
        $floor = (int)$request->floor;
        $check = Table::query()
                ->where('name', $name)
                ->value('name');
        // dd($check);
        if(!is_null($check))
        {
            return $this->errorResponse('Trùng bàn');
        }

        Table::query()
                ->create([
                    'name' => $name,
                    'floor' => $floor,
                ]);
        return $this->successResponse(1,'Thành công rồi nhé!!!');
    }

    public function edit($table_id)
    {
        dd($table_id);
    }
}
