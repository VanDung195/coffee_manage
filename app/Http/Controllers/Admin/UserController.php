<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\Position;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as FacadesView;

class UserController extends Controller
{
    use ResponseTrait;

    protected object $model;
    protected string $table;

    public function __construct()
    {
        $this->model = User::query()->where('is_hidden', false);
        $this->table = (new User())->getTable();
        FacadesView::share('title', ucwords($this->table));
        FacadesView::share('table', $this->table);
    }

    public function index(Request $request) {
        $selected_role = $request->role;
        $selected_shift = $request->shift_id;
        $query = $this->model->clone()
                ->where('role', '<>', 1);
        if(!is_null($selected_role))
        {
            $query->where('role', $selected_role);
        }
        if(!is_null($selected_shift))
        {
            $query->where('shift_id', $selected_shift);
        }

        $users = $query->paginate()->appends($request->all());

        $role = UserRoleEnum::getRole();
        $shift = Shift::query()
                ->where('time', '<>', 0)
                ->get();
        return view('admin.user.index', [
            'users' => $users,
            'role' => $role,
            'shifts' => $shift,
            'selected_role' => $selected_role,
            'selected_shift' => $selected_shift,
        ]);
    }
    public function show($user) {
        dd($user);
    }
    public function edit($user_id)
    {
        $user = User::query()
                ->where('id', $user_id)
                ->first();
        $positions = Position::query()
                    ->where('name', '<>', 'Admin')
                    ->get();
        $shifts = Shift::query()
                ->where('description', '<>', 'Admin')
                ->get();
        return view('admin.user.edit', [
            'user' => $user,
            'positions' => $positions,
            'shifts' => $shifts,
        ]);
    }
    public function update(Request $request)
    {
        User::query()
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'birthdate' => $request->birthdate,
                'phone' => $request->phone,
                'address' => $request->address,
                'account' => $request->account,
                'role' => $request->role,
                'shift_id' => $request->shift,
            ]);

        return redirect()->route('admin.user.index')->with('success', 'Cập nhật thông tin nhân viên thành công!');
    }
    public function destroy(Request $request)
    {
        $user = User::find($request->user_id);
        if(!isset($user))
        {
            return $this->errorResponse('Nhân viên này không tồn tại trong hệ thống! Hãy thử lại su');
        }
        User::query()
                ->where('id', $request->user_id)
                ->update([
                    'is_hidden' => true,
                ]);
        return $this->successResponse([
            'user_id' => $request->user_id,
        ],'Xoá nhân viên thành công!');
    }
}
