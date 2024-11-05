<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\ProductCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        // ensure_user_can_access(AclResource::PRODUCT_CATEGORY_MANAGEMENT);
    }

    public function index()
    {
        //$items = ProductCategory::with('products')->orderBy('name', 'asc')->get();
        // $items = ProductCategory::with('products')->orderBy('name', 'asc')->get();
        return inertia('product-category/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $search = $request->get('filter', '');

        $q = ProductCategory::query();
        $q->orderBy($orderBy, $orderType);
        if (!empty($search)) {
            $q->where('name', 'like', '%' . $search . '%');
            $q->orWhere('description', 'like', '%' . $search . '%');
        }

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:product_categories,name|min:3|max:100',
        ]);

        $item = ProductCategory::create([
            'name' => $request->string('name', ''),
            'description' => $request->string('description', ''),
        ]);

        return response()->json([
            'message' => 'Product category sucessfully created!',
            'data' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:product_categories,name,' . $id . '|min:3|max:100',
        ]);

        $item = ProductCategory::find($id);
        $item->fill([
            'name' => $request->string('name', ''),
            'description' => $request->string('description', ''),
        ]);
        $item->save();

        return response()->json([
            'message' => 'Category updated!',
            'data' => $item,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $item = ProductCategory::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Category successfully deleted!'
        ]);
    }
}
