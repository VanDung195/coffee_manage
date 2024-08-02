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
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    // use ResponseTrait;

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
        // $birthdate = user()->birthdate;
        $birthdate = date('d-m-Y', strtotime(user()->birthdate));;
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

    // public function update(Request $request)
    // {
    //     // $validatedData = $request->validate([
    //     //     'name' => 'required|string|max:255',
    //     //     'birthdate' => 'required|date',
    //     //     'phone' => 'required|string|max:15',
    //     //     'cccd' => 'required|string|max:12',
    //     //     'address' => 'required|string|max:255',
    //     // ]);
    //     $request->validate([
    //         'name' => 'required',
    //         'birthdate' => 'required|date',
    //         'phone' => 'required|string|max:10,',
    //         'cccd' => 'required|string|max:12',
    //         'address' => 'required|string|max:255'
    //     ],[
    //         'name.required' => 'Không được để trống tên.',
    //         'birthdate.required' => 'Không được để trống ngày sinh',
    //         'birthdate.date' => 'Sai định dạng.',
    //         'phone.max' => 'SDT chỉ tối đa 10 ký tự.',
    //         'phone.string' => 'SDT không được chứa ký tự đặc biệt.',
    //         'cccd.required' => 'Không được để trống CCCD.',
    //         'cccd.max' => 'CCCD chỉ tối đa 12 ký tự.',
    //         'cccd.string' => 'CCCD không được chứa ký tự đặc biệt.',
    //         'address.required' => 'Không được để trống địa chỉ.',
    //         'address.string' => 'Địa chỉ không được chứa ký tự đặc biệt.',
    //         'address.max' => 'Địa chỉ chỉ tối đa 255 ký tự.',
    //     ]); 

    //     if(!auth()->check())
    //     {
    //         return redirect()->route('login')->with('error', 'Dang nhap da!');
    //     }
    //     $id = user()->id;
    //     $name = $request->name;
    //     $birthdate = $request->birthdate;
    //     $phone = $request->phone;
    //     $cccd = $request->cccd;
    //     $address = $request->address;

    //     $user = User::findOrFail($id);
    //     $user->name = $name;
    //     $user->birthdate = $birthdate;
    //     $user->phone = $phone;
    //     $user->CCCD = $cccd;
    //     $user->address = $address;
    //     $user->save();

    //     user()->fresh();

    //     return redirect()->route('user.index')->with('success', 'Cap nhat thong tin ca nhan thanh cong!!!');
    // }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'birthdate' => 'required|date',
                'phone' => 'required|string|max:10,',
                'cccd' => 'required|string|max:12',
                'address' => 'required|string|max:255'
            ],[
                'name.required' => 'Không được để trống tên.',
                'birthdate.required' => 'Không được để trống ngày sinh',
                'birthdate.date' => 'Sai định dạng.',
                'phone.required' => 'SDT khong duoc de trong',
                'phone.max' => 'SDT chỉ tối đa 10 ký tự.',
                'phone.string' => 'SDT không được chứa ký tự đặc biệt.',
                'cccd.required' => 'Không được để trống CCCD.',
                'cccd.max' => 'CCCD chỉ tối đa 12 ký tự.',
                'cccd.string' => 'CCCD không được chứa ký tự đặc biệt.',
                'address.required' => 'Không được để trống địa chỉ.',
                'address.string' => 'Địa chỉ không được chứa ký tự đặc biệt.',
                'address.max' => 'Địa chỉ chỉ tối đa 255 ký tự.',
            ]); 
    
            if(!auth()->check())
            {
                // return redirect()->route('login')->with('error', 'Dang nhap da!');
                // return 
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
            return response()->json(['success' => true]);
            // return redirect()->route('user.index')->with('success', 'Cap nhat thong tin ca nhan thanh cong!!!');
        } catch (ValidationException $e) {
            // dd($e);
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                // errors function is return $this->validator->errors()->messages();
            ],422);

        }
        
    }
}
