<?php

namespace App\Http\Controllers\User;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\SalaryInformation;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        // dd(session()->all());
        // dd(user());
        if(!auth()->check())
        {
            return redirect()->route('login')->with('error', 'Dang nhap da!');
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

    public function edit()
    {
        if(!auth()->check())
        {
            return redirect()->route('login')->with('error', 'Dang nhap da!');
        }
        $id = user()->id;
        $name = user()->name;
        $birthdate = user()->birthdate;
        $gender = user()->gender ? 'Nam' : 'Nu';
        $phone = user()->phone;
        $address = user()->address;
        $role = user()->role;
        $role_name = Str::title(UserRoleEnum::getKey($role));
        $shift = Shift::query()
                ->where('id', user()->shift_id)
                ->value('description');
        $id = user()->id;
        $cccd = user()->CCCD;
        return view('user.edit', [
            'id' => $id,
            'name' => $name,
            'birthdate' => $birthdate,
            'cccd' => $cccd,
            'gender' => $gender,
            'phone' => $phone,
            'address' => $address,
            'role_name' => $role_name,
            'shift' => $shift,
        ]);
    }

    public function update(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'birthdate' => 'required|date',
        //     'phone' => 'required|string|max:15',
        //     'cccd' => 'required|string|max:12',
        //     'address' => 'required|string|max:255',
        // ]);
        $request->validate([
            'name' => 'required',
            'birthdate' => 'required|date',
            'phone' => 'required|string|max:10,',
            'cccd' => 'required|string|max:12',
            'address' => 'required|string|max:255'
        ],[
            'name.required' => 'Name is reiquired',
            'birthdate.required' => 'Birthdate is required',
            'phone.max' => 'phone max 15 characters',
        ]); 

        if(!auth()->check())
        {
            return redirect()->route('login')->with('error', 'Dang nhap da!');
        }
        $id = user()->id;
        $name = $request->name;
        $birthdate = $request->birthdate;
        $phone = $request->phone;
        $cccd = $request->cccd;
        $address = $request->address;

        $user = User::findOrFail($id);
        $user->name = $name;
        $user->birthdate = $birthdate;
        $user->phone = $phone;
        $user->CCCD = $cccd;
        $user->address = $address;
        $user->save();

        user()->fresh();

        return redirect()->route('user.index')->with('success', 'Cap nhat thong tin ca nhan thanh cong!!!');
    }
}
