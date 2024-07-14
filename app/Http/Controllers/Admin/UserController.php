<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as FacadesView;

class UserController extends Controller
{
    protected object $model;
    protected string $table;
    
    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();
        FacadesView::share('title', ucwords($this->table));  
        FacadesView::share('table', $this->table);
    }

    public function index(Request $request) {
        $selected_role = $request->role;
        $selected_shift = $request->shift_id;
        $query = $this->model->clone()
                // ->with('shift:id,description')
                ->where('role', '<>', 1);
        if(!is_null($selected_role))
        {
            $query->where('role', $selected_role);
        }
        if(!is_null($selected_shift))
        {
            // $query->whereHas('shift', function($q) use ($selected_shift) {
            //     return $q->where('id', $selected_shift);
            // });
            $query->where('shift_id', $selected_shift);
        }

        $users = $query->paginate()->appends($request->all());

        // $users = User::query()
        //         ->orderBy('role', 'asc')
        //         ->where('role', '<>', 1)
        //         ->paginate();
        $role = UserRoleEnum::getRole();
        $shift = Shift::query()
                ->where('time', '<>', 0)
                ->get();
        // $role = UserRoleEnum::getKeys();
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
        return view('admin.user.edit', [
            'user' => $user,
        ]);
    }
    public function update()
    {

    }
}
