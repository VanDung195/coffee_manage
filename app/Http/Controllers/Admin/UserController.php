<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function index() {
        $users = User::query()
                ->orderBy('role', 'asc')
                ->where('role', '<>', 1)
                ->paginate();

        return view('admin.user.index', [
            'users' => $users,
        ]);
    } 
    public function show($user) {
        dd($user);
    }
}
