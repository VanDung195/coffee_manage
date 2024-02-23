<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Enums\TableIsPaidEnum;
use App\Http\Controllers\TableController;
use App\Models\Attendance;

class TestController extends Controller
{
    public function test()
    {
        $item = MenuItem::query();
        $items = MenuItem::query()
                    ->where('name', 'like', '%' . 'Sinh tố' . '%')->get();
        // // dd($items);
        // foreach ($items as $key => $value) {
        //     // dd($value->name, $value->id);
        //     echo $value;
        // }
        // return TableController::class;
        // $isPaids = TableIsPaidEnum::getKeys();
        // dd($isPaids);
        // foreach ($isPaids as $key => $value) {
        //     dd($key,$value);
        // }

        // return Table::query()->get('name');
    }
    public function create() {
        return view('testee');
    }
    public function test2(Request $request)
    {
        dd($request->all());
    }
}
