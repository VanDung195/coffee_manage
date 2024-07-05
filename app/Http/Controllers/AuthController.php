<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function process_login(Request $request) 
    {
        // dd(auth()->check());
        if(auth()->check())
        {
            return redirect()->route('login')->with('error', 'Đăng xuất đã!');
        }
        // $account = $request->account;
        // $password = $request->password;
        
        // $user = User::query()
        //         ->where('account','=', $account)
        //         ->first();
        // if($user) {
        //     $user = User::query()
        //             ->where('password', $password)->first();
        // }
        // dd($user);
        // $user = $request->only('account','password');
        // $role = User::query()
        //             ->where('account', $request->account)
        //             ->pluck('role');
        // dd($role);
        // $clientIpAddress = $request->getClientIp();
        // dd($clientIpAddress);
        $user = User::query()
                ->where('account', $request->account)
                ->first();
        if($user && Hash::check($request->password, $user->password))
        {
            auth()->login($user,true);

            $available_role = [1, 2, 3];
            if(!in_array(user()->role, $available_role))
            {
                return redirect()->route('table');
            }
            return redirect()->route('table');
        }
        return redirect()->route('login')->with('error', 'Dang nhap that bai');

        // $check_exist = true;
        // dd($user);
        // // if(Auth::attempt($user)){
        // //     $user = Auth::user();
        // //     $check_exist = true;
        // //     auth()->login($user, true);
        // //     // dd(user()->role);
        // //     // if(user()->role )
        // return redirect()->route('table');

        // // }

        // return redirect()->route('login')->with('error', 'Dang nhap that bai');
    }
    public function register() {
        $roleForRegister = UserRoleEnum::getRoleForRegister();
        $shift = getAndCacheShift();
        return view('auth.register', [
            'roles' => $roleForRegister,
            'shifts' => $shift,
        ]);
    }
    public function process_register(Request $request)
    {   
        // dd($request->all());
        $user = User::query()
                ->where('account', $request->account)->first();

        if(isset($user)) {
            return redirect()->route('login')->with('error', 'trung');
        }

        $password = Hash::make($request->password);
        if(is_null($user))
        {
            $user = new User();
            $user->name = $request->name;
            $user->account = $request->account;
            $user->password = $password;
            $user->gender = 1;
            $user->phone = $request->account;
            $user->role = $request->role;
            $user->shift_id = $request->shift;
        }
        $user->save();
        return redirect()->route('table');
    }
    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
