<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseTrait;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $categories = MenuCategory::query()
                    ->where('is_hidden', false)
                    ->orderBy('is_hidden', 'asc')
                    ->get();
        return view('admin.menu_category.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return view('admin.menu_category.create');
    }

    public function store(Request $request)
    {
        MenuCategory::create([
            'name' => $request->menu_category_name,
            'is_hidden' => false,
        ]);
        return redirect()->route('admin.menu_categories.index')->with('success', 'Thêm loại món thành công!');
    }

    public function edit($item_id)
    {
        $category = MenuCategory::query()
                    ->where('id', $item_id)
                    ->first();
        return view('admin.menu_category.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request)
    {
        MenuCategory::query()
                    ->where('id', $request->menu_category_id)
                    ->update([
                        'name' => $request->name,
                    ]);
        return redirect()->route('admin.menu_categories.index')->with('success', 'Cập nhật loại món thành công!');
    }

    public function destroy(Request $request)
    {
        $menu_item_check = MenuItem::query()
                            ->where('menu_category_id',$request->menu_category_id)
                            ->where('is_hidden', false)
                            ->first();
        if(!is_null($menu_item_check))
        {
            return $this->errorResponse('Đã xoá hết món đâu, hãy kiểm tra và thử lại sau!');

        }
        MenuCategory::query()
                    ->where('id', $request->menu_category_id)
                    ->update([
                        'is_hidden' => true,
                    ]);
        return $this->successResponse([
            'id' => $request->menu_category_id,
        ],'Đã xoá loại món thành công');
    }
}
