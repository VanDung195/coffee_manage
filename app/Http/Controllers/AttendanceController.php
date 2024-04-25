<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $users = User::query()->get();
        return view('attendance.index',[
            'users' => $users,
        ]);
    }
}
