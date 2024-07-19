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
                // ->latest();
        if(!is_null($selected_category))
        {
            //whereHas (WHERE EXISTS vào truy vấn sql)
            $query->whereHas('menu_category', function($q) use($selected_category){
                return $q->where('id', $selected_category);
            });
            // dd($query->toSql(), $query->getBindings());
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
                // ->where('menu_category_id', $category)
                ->where('name', $name)
                ->first();
        // dd($check);
        if($check)
        {
            return redirect()->back()->with('error','Trùng');
        }
        MenuItem::create([
            'menu_category_id' => $category,
            'name' => $name,
            'price' => $price * 1000,
        ]);
        return redirect()->back()->with('success', 'Thêm món thành công rồi nhé!');
    }

    public function edit($item_id)
    {
        $item = MenuItem::query()
                ->where('id', $item_id)
                ->first();
        // $category = MenuCategory::find($item->menu_category_id);

        // $item = $this->model
        //         ->clone()
        //         ->with('menu_category:id,name')
        //         ->where('id', $item_id)
        //         ->first();
        $menu_categories = MenuCategory::query()
                ->get();
        // dd($item);
        // dd($menu_categories);
        // dd($item->menu_category->id);
        return view('admin.menu_item.edit',[
            'item' => $item,
            'categories' => $menu_categories,
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
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
        // MenuItem::query()
        //         ->where('id', $request->menu_item_id)
        //         ->update([
        //             'is_hidden' => 1,
        //         ]);
        $menu_item = MenuItem::findOrFail($request->menu_item_id);
        $menu_item->is_hidden = 1;
        $menu_item->save();
        return redirect()->back();
    }
    // public function search(Request $request): JsonResponse
    // {
    //     if($request->ajax())
    //     {
    //         $output = '';
    //         $items = MenuItem::query()
    //                 ->where('name', 'like', '%' . $request->search . '%')->get();
            
    //         if($items)
    //         {
    //             $output .= '<ul>';
    //             foreach ($items as $key => $item) {
    //                 $output .= '<li><a href="#" data-id="' . $item->id . '" class="item-link">' . $item->name . '</a></li>';
    //             }
    //             $output .= '</ul>';
    //         }
    //     }
    //     return $this->successResponse($output);
    // }
}
