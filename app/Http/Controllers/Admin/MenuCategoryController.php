<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::query()
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
        ]);
        return redirect()->route('admin.menu_categories.index')->with('success', 'Thanh cong roi nhe');
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
        return redirect()->route('admin.menu_categories.index')->with('success', 'thanh cong roi nhe');
    }

    public function destroy(Request $request)
    {
        // MenuCategory::destroy($request->menu_category_id);
        $menu_category = MenuCategory::find($request->id);
        $menu_category->delete();
        return redirect()->back();
    }
}
