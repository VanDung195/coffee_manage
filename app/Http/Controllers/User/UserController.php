<?php

namespace App\Http\Controllers\User;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        // dd(user());
        // $user = user();
        $name = user()->name;
        $phone = user()->phone;
        $address = user()->address;
        $role = user()->role;
        // $role = UserRoleEnum::getKey()
        $role_name = Str::title(UserRoleEnum::getKey($role));
        return view('user.index', [
            // 'user' => $user,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'role_name' => $role_name,
        ]);
    }
}
