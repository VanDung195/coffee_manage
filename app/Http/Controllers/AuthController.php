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
        if(auth()->check())
        {
            $available_role = [1, 2, 3];
            if(!in_array(user()->role, $available_role))
            {
                return redirect()->route('user.index');
            }
            return redirect()->route('table');
        }
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
                // return redirect()->route('table');
                return redirect()->route('user.index');
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
            return redirect()->route('login')->with('error', 'Tài khoản đã tồn tại trong hệ thống!');
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
            $user->phone = $request->phone;
            $user->is_hidden = 0;
        }
        $user->save();
        return redirect()->route('admin.user.index')->with('success', 'Thêm nhân viên thành công');
    }
    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }

    public function reset_password()
    {
        return view('auth.resetpassword');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|max:15',
            'confirm_password' => 'required|min:8|max:15'
        ], [
            'old_password.required' => 'Không được để trống!',
            'new_password.required' => 'Không được để trống!',
            'new_password.min' => 'Tối thiểu 8 ký tự!',
            'new_password.max' => 'Tối đa 15 ký tự!',
            'confirm_password.required' => 'Không được để trống!',
            'confirm_password.min' => 'Tối thiểu 8 ký tự!',
            'confirm_password.max' => 'Tối đa 15 ký tự!',
        ]);
        
        $errors = [];
        if(!Hash::check($request->old_password, user()->password))
        {
            // return back()->withErrors([
            //     'old_password' => 'Mật khẩu cũ không chính xác.',
            // ]);
            $errors['old_password'] = 'Mật khẩu cũ không chính xác.';
        }
        if(Hash::check($request->new_password, user()->password))
        {
            $errors['new_password'] = 'Mật khẩu mới trùng với mật khẩu cũ.';
        }
        if($request->confirm_password != $request->new_password)
        {
            // return back()->withErrors([
            //     'confirm_password' => 'Mật khẩu mới không khớp. Hãy nhập lại.'
            // ]);
            $errors['confirm_password'] = 'Mật khẩu mới không khớp. Hãy nhập lại.';
        }
        if(!empty($errors))
        {
            return back()->withErrors($errors);
        }

        $user = User::findOrFail(user()->id);
        $new_password = Hash::make($request->new_password);
        $user->password = $new_password;
        $user->save();
        user()->fresh();
        return redirect()->route('user.index')->with('success', 'Thanh cong roi nhe.');
    }
}
