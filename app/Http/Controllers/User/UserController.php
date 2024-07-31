<?php

namespace App\Http\Controllers\User;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\SalaryInformation;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        if(!auth()->check())
        {
            return redirect()->route('login')->with('error', 'Đăng xuất đã!');
        }
        // dd(user());
        // $user = user();
        $id = user()->id;
        $name = user()->name;
        $birthdate = user()->birthdate;
        $gender = user()->gender ? 'Nam' : 'Nu';
        $phone = user()->phone;
        $address = user()->address;
        $role = user()->role;
        // $role = UserRoleEnum::getKey()
        $role_name = Str::title(UserRoleEnum::getKey($role));
        $shift = Shift::query()
                ->where('id', user()->shift_id)
                ->value('description');
        $id = user()->id;
        $salary_information = SalaryInformation::query()
                            ->where('user_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        // dd($salary_information);
        return view('user.index', [
            // 'user' => $user,
            'id' => $id,
            'name' => $name,
            'birthdate' => $birthdate,
            'gender' => $gender,
            'phone' => $phone,
            'address' => $address,
            'role_name' => $role_name,
            'shift' => $shift,
            'salary_information' => $salary_information,
        ]);
    }

    public function edit($user_id)
    {
        dd($user_id);
    }
}
