<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Party;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockUpdate;
use App\Models\StockUpdateDetail;
use App\Models\Supplier;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;

class ProductController extends Controller
{
    // public function index(Request $request)
    // {
    //     // ensure_user_can_access(AclResource::PRODUCT_LIST);

    //     $filter = [
    //         'type' => (int)$request->get('type', $request->session()->get('product.filter.type', -1)),
    //         'active' => (int)$request->get('active', $request->session()->get('product.filter.active', -1)),
    //         'category_id' => (int)$request->get('category_id', $request->session()->get('product.filter.category_id', -1)),
    //         'supplier_id' => (int)$request->get('supplier_id', $request->session()->get('product.filter.supplier_id', -1)),
    //         'stock_status' => (int)$request->get('stock_status', $request->session()->get('product.filter.stock_status', -1)),
    //         'search' => $request->get('search', $request->session()->get('product.filter.search', '')),
    //     ];

    //     $filter_active = true;
    //     if ($request->get('action') == 'reset') {
    //         $filter_active = false;
    //         $filter['type'] = -1;
    //         $filter['active'] = -1;
    //         $filter['category_id'] = -1;
    //         $filter['supplier_id'] = -1;
    //         $filter['stock_status'] = -1;
    //         $filter['search'] = '';
    //     }


    //     $q = Product::query();

    //     if ($filter['type'] != -1) {
    //         $q->where('type', '=', $filter['type']);
    //     }
    //     if ($filter['active'] != -1) {
    //         $q->where('active', '=', $filter['active']);
    //     }
    //     if ($filter['category_id'] != -1) {
    //         $q->where('category_id', '=', $filter['category_id']);
    //     }
    //     if ($filter['supplier_id'] != -1) {
    //         $q->where('supplier_id', '=', $filter['supplier_id']);
    //     }

    //     if ($filter['stock_status'] == 0) {
    //         $q->where('stock', '=', 0);
    //     } else if ($filter['stock_status'] == 1) {
    //         $q->whereRaw('stock < minimum_stock');
    //     } else if ($filter['stock_status'] == 2) {
    //         $q->whereRaw('stock > 0');
    //     }

    //     if (!empty($filter['search'])) {
    //         $q->where('code', 'like', '%' . $filter['search'] . '%');
    //         $q->orWhere('description', 'like', '%' . $filter['search'] . '%');
    //     }

    //     $categories = ProductCategory::orderBy('name', 'asc')->get();
    //     $suppliers = Supplier::where('type', '=', Party::TYPE_SUPPLIER)
    //         ->orderBy('name', 'asc')->get();
    //     $items = $q->with(['category', 'supplier'])
    //         ->orderBy('code', 'asc')
    //         ->paginate(10);

    //     $request->session()->put('product.filter.type', $filter['type']);
    //     $request->session()->put('product.filter.active', $filter['active']);
    //     $request->session()->put('product.filter.category_id', $filter['category_id']);
    //     $request->session()->put('product.filter.supplier_id', $filter['supplier_id']);
    //     $request->session()->put('product.filter.stock_status', $filter['stock_status']);
    //     $request->session()->put('product.filter.search', $filter['search']);

    //     return view('admin.product.index', compact('items', 'filter', 'suppliers', 'categories', 'filter_active'));
    // }

    public function index()
    {
        return inertia('product/Index');
    }

    public function editor($id = 0)
    {
        $product = $id ? Product::findOrFail($id) : new Product();
        $data = $product->toArray();
        $data['id_formatted'] = $product->idFormatted();
        return inertia('product/Editor', [
            'data' => $data,
            // 'types' => [
            //     Product::NON_STOCKED => Product::formatType(Product::NON_STOCKED),
            //     Product::STOCKED => Product::formatType(Product::STOCKED),
            //     Product::SERVICE => Product::formatType(Product::SERVICE),
            // ]
            'types' => [[
                    'id' => Product::NON_STOCKED,
                    'label' => Product::formatType(Product::NON_STOCKED),
                ], [
                    'id' => Product::STOCKED,
                    'label' => Product::formatType(Product::STOCKED),
                ], [
                    'id' => Product::SERVICE,
                    'label' => Product::formatType(Product::SERVICE),
                ],
            ]
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $search = $request->get('filter', '');

        $q = Product::query();
        $q->orderBy($orderBy, $orderType);
        if (!empty($search)) {
            $q->where('code', 'like', '%' . $search . '%');
            $q->orWhere('description', 'like', '%' . $search . '%');
        }

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }


    public function detail(Request $request, $id)
    {
        $item = Product::with(['category', 'supplier'])->findOrFail($id);
        $stock_update_details = DB::select('
            select
              u.id update_id, u.id2 update_id2, u.type update_type, u.created_datetime update_created_datetime,
              a.id party_id, a.name party_name, a.type party_type,
              d.quantity, d.cost, d.price
            from stock_update_details d
            inner join products p on p.id=d.product_id
            inner join stock_updates u on u.id=d.update_id
            left join parties a on a.id = u.party_id
            where p.id=:id and u.status=:status
            order by u.id desc limit 50', [
            'id' => $item->id,
            'status' => StockUpdate::STATUS_COMPLETED
        ]);
        return view('admin.product.detail', compact('item', 'stock_update_details'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            // ensure_user_can_access(AclResource::ADD_PRODUCT);
            $item = new Product();
            $item->type = Product::STOCKED;
            $item->active = true;
            $item->price = 0;
            $item->cost = 0;
            $item->stock = 0;
        } else {
            // ensure_user_can_access(AclResource::EDIT_PRODUCT);
            $item = Product::find($id);
            if (!$item) {
                return redirect('admin/product')->with('warning', 'Produk tidak ditemukan.');
            }
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:products,code,' . $id . '|max:100',
            ], [
                'code.required' => 'Nama / kode produk harus diisi.',
                'code.unique' => 'Nama / kode produk sudah digunakan.',
                'code.max' => 'Nama / kode produk terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $data = ['Old Data' => $item->toArray()];
            $newData = $request->all();

            $initial_stock = $item->stock;
            $new_stock = $newData['stock'];

            DB::beginTransaction();

            if (empty($newData['category_id']) || $newData['category_id'] == -1) {
                $newData['category_id'] = null;
            }

            if (empty($newData['supplier_id']) || $newData['supplier_id'] == -1) {
                $newData['supplier_id'] = null;
            }

            fill_with_default_value($newData, ['active', 'stock', 'cost', 'price'], 0.);

            $newData['stock'] = number_from_input($newData['stock']);
            $newData['cost'] = number_from_input($newData['cost']);
            $newData['price'] = number_from_input($newData['price']);

            $item->fill($newData);
            $item->save();

            if ($new_stock != $initial_stock) {
                $qty = $new_stock - $initial_stock;
                $update = new StockUpdate();
                $update->type = StockUpdate::TYPE_SINGLE_ADJUSTMENT;
                $update->id2 = StockUpdate::getNextId2($update->type);
                $update->total_cost = $qty * $item->cost;
                $update->total_price = $qty * $item->price;
                $update->open();
                $update->close(StockUpdate::STATUS_COMPLETED);
                $update->save();

                $detail = new StockUpdateDetail();
                $detail->id = 1;
                $detail->update_id = $update->id;
                $detail->product_id = $item->id;
                $detail->quantity = $qty;
                $detail->cost = $item->cost;
                $detail->price = $item->price;
                $detail->save();
            }

            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::PRODUCT_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Produk',
                'Produk ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            DB::commit();

            return redirect('admin/product')->with('info', 'Produk telah disimpan.');
        }

        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::where('type', Party::TYPE_SUPPLIER)->orderBy('name', 'asc')->get();
        return view('admin.product.edit', compact('item', 'categories', 'suppliers'));
    }

    public function duplicate(Request $request, $sourceId)
    {
        ensure_user_can_access(AclResource::ADD_PRODUCT);

        $item = Product::findOrFail($sourceId);
        $item = $item->replicate();
        $item->id = 0;

        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        return view('admin.product.edit', compact('item', 'categories', 'suppliers'));
    }

    public function delete(Request $request, $id)
    {
        ensure_user_can_access(AclResource::DELETE_PRODUCT);
        $item = Product::findOrFail($id);
        $productUsedCount = DB::select("select count(0) as count from stock_update_details where product_id=:id", [":id" => $item->id])[0]->count;

        DB::beginTransaction();
        if ($productUsedCount > 0) {
            $item->active = false;
            $item->save();
            $msg = ' telah dinonaktifkan.';
        } else {
            $item->delete();
            $msg = ' telah dihapus.';
        }

        $message = 'Produk ' . e($item->name) . $msg;
        UserActivity::log(
            UserActivity::PRODUCT_MANAGEMENT,
            'Hapus Produk',
            $message,
            $item->toArray()
        );
        DB::commit();

        return redirect('admin/product')->with('info', $message);
    }

    public function restore($id)
    {
        ensure_user_can_access(AclResource::DELETE_PRODUCT);

        if (!$item = Product::withTrashed()->find($id))
            $message = 'Produk tidak ditemukan.';
        else {
            $item->restore();
            $message = 'Produk #' . e($item->idFormatted()) . ' telah dipulihkan.';
            UserActivity::log(
                UserActivity::PRODUCT_MANAGEMENT,
                'Pulihkan Produk',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/product')->with('info', $message);
    }
}
