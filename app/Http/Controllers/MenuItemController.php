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
    public function index(Request $request)
    {
        $selected_category = $request->get('category');
        // $query = $this->model->
        if(!is_null($selected_category))
        {
            dd(1);
        }
        $menu_categories = MenuCategory::query()
                    ->get();
        $menu_items = MenuItem::query()
                        ->get();
        return view('admin.menu_item.index', [
            'menu_items' => $menu_items,
            'menu_categories' => $menu_categories,
        ]);
    }
}
