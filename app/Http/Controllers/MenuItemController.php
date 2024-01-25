<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    use ResponseTrait;

    public function search(Request $request): JsonResponse
    {
        if($request->ajax())
        {
            $output = '';
            $items = MenuItem::query()
                    ->where('name', 'like', '%' . $request->search . '%')->get();
            
            if($items)
            {
                $output .= '<ul>';
                foreach ($items as $key => $item) {
                    $output .= '<li><a href="#" data-id="' . $item->id . '" class="item-link">' . $item->name . '</a></li>';
                }
                $output .= '</ul>';
            }
        }
        return $this->successResponse($output);
    }
}
