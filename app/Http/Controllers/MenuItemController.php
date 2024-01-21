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
            // dd($item);
            if($items)
            {
                $output .= '<tr>
                <td>
                    <select name="item_select" class="form-control">';
                foreach ($items as $key => $item) {
                    $output .= '<option value="' . $item->id . '">' . $item->name . '</option>';
                }
                $output .= '</select>
                    </td>
                </tr>';
            }
        }
        return $this->successResponse($output);
    }
}
