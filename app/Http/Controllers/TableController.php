<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Table;

class TableController extends Controller
{
    public function index()
    {
        $table = Table::query()
        ->orderBy('stt', 'asc')
        ->paginate(10);
        $items = MenuItem::query()
                    ->get();
        return view('admin.index',[
            'table' => $table,
            'items' => $items,
        ]);
    }
}
