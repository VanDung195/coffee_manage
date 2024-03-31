<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function process_login(Request $request) 
    {
        $account = $request->account;
        $password = $request->password;
        
        $user = User::query()
                ->where('account','=', $account)
                ->first();
        if($user) {
            $user = User::query()
                    ->where('password', $password)->first();
        }
        dd($user);
    }
}
