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
        $this->model = MenuItem::query();
        $this->table = (new MenuItem())->getTable();
    }
    
    public function index(Request $request)
    {
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
        $data = $query->paginate(10)->appends($request->all());
        
        $menu_categories = MenuCategory::query()
                    ->get();
        // $menu_items = MenuItem::query()
        //                 ->get();
        return view('admin.menu_item.index', [
            'data' => $data,
            'menu_categories' => $menu_categories,
            'selected_category' => $selected_category,
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
            // dd($request->name, $request->price);
            return redirect()->back()->with('error', 'Không được để trống');
        }
        $check = MenuItem::query()
                ->where('menu_category_id', $category)
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
