<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    use ResponseTrait;

    protected object $model;
    protected string $table;

    public function __construct()
    {
        $this->model = MenuItem::query()->where('is_hidden', false);
        $this->table = (new MenuItem())->getTable();
    }

    public function index(Request $request)
    {
        $selected_sort = $request->sort;

        $selected_category = $request->get('category');
        $query = $this->model->clone()
                ->with('menu_category:id,name');
        if(!is_null($selected_category))
        {
            //whereHas (WHERE EXISTS vào truy vấn sql)
            $query->whereHas('menu_category', function($q) use($selected_category){
                return $q->where('id', $selected_category);
            });
        }
        if(!is_null($selected_sort))
        {
            if($selected_sort == 'asc' || $selected_sort == 'desc')
            {
                $query->orderBy('price', $selected_sort);
            }
        }
        $data = $query->paginate(15)->appends($request->all());

        $menu_categories = MenuCategory::query()
                    ->where('is_hidden', false)
                    ->get();
        return view('admin.menu_item.index', [
            'data' => $data,
            'menu_categories' => $menu_categories,
            'selected_category' => $selected_category,
            'selected_sort' => $selected_sort,
        ]);
    }

    public function create()
    {
        $menu_categories = MenuCategory::query()
                            ->get();
        return view('admin.menu_item.create',[
            'menu_categories' => $menu_categories,
        ]);
    }

    public function store(Request $request)
    {
        $category = $request->category;
        $name = $request->name;
        $price = $request->price;
        if($name == null || $price == null)
        {
            return redirect()->back()->with('error', 'Không được để trống');
        }
        $check = MenuItem::query()
                ->where('name', $name)
                ->first();
        if($check)
        {
            return redirect()->back()->with('error','Tên món đã tồn tại trong hệ thống!');
        }
        MenuItem::create([
            'menu_category_id' => $category,
            'name' => $name,
            'price' => $price * 1000,
            'is_hidden' => false,
        ]);
        return redirect()->route('admin.menu_items.index')->with('success', 'Thêm món thành công rồi nhé!');
    }

    public function edit($item_id)
    {
        $item = MenuItem::query()
                ->where('id', $item_id)
                ->first();
        $menu_categories = MenuCategory::query()
                ->get();
        return view('admin.menu_item.edit',[
            'item' => $item,
            'categories' => $menu_categories,
        ]);
    }

    public function update(Request $request)
    {
        MenuItem::query()
                ->where('id', $request->menu_item_id)
                ->update([
                    'menu_category_id' => $request->category,
                    'name' => $request->name,
                    'price' => $request->price * 1000,
                ]);
        return redirect()->route('admin.menu_items.index')->with('success', 'Cập nhật món thành công');
    }

    public function destroy(Request $request)
    {
        $check = MenuItem::query()
                ->where('id', $request->menu_item_id)
                ->value('id');
        if(is_null($check))
        {
            return $this->errorResponse('Lỗi, hãy thử lại sau!');
        }
        $menu_item = MenuItem::findOrFail($request->menu_item_id);
        $menu_item->is_hidden = 1;
        $menu_item->save();
        return $this->successResponse([
            'menu_item_id' => $request->menu_item_id,
        ], 'Xoá món thành công!');
    }
}
