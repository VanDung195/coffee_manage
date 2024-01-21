<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        // $items = MenuItem::query()
        //             ->where('name', 'like', '%' . 'Sinh tố' . '%')->get();
        // // dd($items);
        // foreach ($items as $key => $value) {
        //     // dd($value->name, $value->id);
        //     echo $value;
        // }
        return TableController::class;
    }
    public function create() {
        return view('test');
    }
    public function test2(Request $request)
    {
        dd($request->all());
    }
}
