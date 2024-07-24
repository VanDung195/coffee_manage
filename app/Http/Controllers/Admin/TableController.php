<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TableStausEnum;
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
                ->where('is_hidden', 0)
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
            return $this->errorResponse('Bàn đã tồn tại trong hệ thống!!!');
        }

        // Table::query()
        //         ->create([
        //             'name' => $name,
        //             'floor' => $floor,
        //         ]);
        $table = new Table();
        $table->name = $name;
        $table->floor = $floor;
        $table->invoice_id = 0;
        $table->is_paid = 0;
        $table->status = TableStausEnum::getKey(1);
        $table->is_hidden = 0;
        $table->save();
        return $this->successResponse(1,'Thành công rồi nhé!!!');
    }

    public function edit($table_id)
    {
        // dd($table_id);
        $table = Table::query()
                    ->where('id', $table_id)
                    // ->first();
                    ->findOrFail($table_id);
        // dd($table);
        return view('admin.table.edit', [
            'table' => $table,
        ]); 

    }

    public function update(Request $request)
    {
        $name = $request->name;
        $floor = (int)$request->floor;
        $table_id = (int)$request->table_id;
        $check = Table::query()
                ->where('id', '<>', $table_id)
                ->where('name', $name)
                ->value('name');
        if(!is_null($check))
        {
            return $this->errorResponse('Bàn đã tồn tại trong hệ thống!!!');
        }

        Table::query()
            ->where('id', $table_id)
            ->update([
                'name' => $name,
                'floor' => $floor,
            ]); 
        return $this->successResponse(1,'Cập nhật bàn thành công! sẽ điều hướng sau 3 giây.');
    }

    public function destroy(Request $request)
    {
        // dd($request->table_id);
        $table_id = (int)$request->table_id;
        $check = Table::query()
                ->where('id', $table_id)
                ->value('id');
        if(is_null($check))
        {
            return $this->errorResponse('Không tồn tại bàn trong hệ thống!');
        }

        Table::query()
            ->where('id', $table_id)
            ->update([
                'is_hidden' => 1,
            ]);
        return $this->successResponse([
            'id' => $table_id,
        ], 'Xoá bàn thành công!');
    }
}
