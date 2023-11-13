<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter = new \StdClass();
        $filter->category_id = $request->category_id;
        if ($filter->category_id === null)
            $filter->category_id = 'all';

        $filter->supplier_id = $request->supplier_id;
        if ($filter->supplier_id == null)
            $filter->supplier_id = 'all';

        $filter->type = $request->type;
        if ($filter->type == null)
            $filter->type = 'all';

        $filter->active = $request->active;
        if ($filter->active == null)
            $filter->active = 'all';

        $query = Product::query();
        if ($filter->category_id != 'all')
            $query->where('category_id', '=', $filter->category_id);
        if ($filter->supplier_id != 'all')
            $query->where('supplier_id', '=', $filter->supplier_id);
        if ($filter->type != 'all')
            $query->where('type', '=', $filter->type);
        if ($filter->active != 'all')
            $query->where('active', '=', $filter->active);

        $products = $query->get();

        return view('admin.product.index', [
            'filter' => $filter,
            'items' => $products,
            'categories' => ProductCategory::all(),
            'suppliers' => Party::where('type', '=', Party::TYPE_SUPPLIER),
        ]);
    }

    public function view($id)
    {
        $data = $this->getProductModel()->find($id);
        $data->category = $this->getProductCategoryModel()->find((int)$data->category_id);
        $data->supplier = $this->getPartyModel()->find((int)$data->supplier_id);
        $data->last_supplier = $this->getPartyModel()->find((int)$data->last_supplier_id);

        $stockUpdates = $this->db->query(
            "select
             sud.quantity, sud.cost, sud.price,
             su.code, su.id, su.type, su.datetime,
             p.name party_name
             from stock_update_details sud
             inner join stock_updates su on su.id = sud.parent_id
             left join parties p on p.id = su.party_id
             where sud.product_id=$data->id
             and su.status=" . StockUpdate::STATUS_COMPLETED
        )->getResultObject();
        return view('product/view', [
            'data' => $data,
            'stockUpdates' => $stockUpdates
        ]);
    }

    public function edit($id, $duplicate = false)
    {
        $model = $this->getProductModel();
        $addProductAfterSave = $this->request->getPost('add_product_after_save');

        if ($id == 0) {
            $item = new Product();
            $item->type = Product::TYPE_STOCKED;
            $item->costing_method = Product::COSTING_METHOD_LAST;
            $item->uom = 'bh';
            $adjustmentType = StockUpdate::UPDATE_TYPE_INITIAL_STOCK;
        } else {
            $item = $model->find($id);
            $adjustmentType = StockUpdate::UPDATE_TYPE_MANUAL_AJDUSTMENT;
            if (!$item) {
                return redirect()->to(base_url('products'))->with('warning', 'Produk tidak ditemukan.');
            }
        }

        if ($duplicate) {
            unset($item->id);
        }

        $errors = [];
        $oldStock = $item->stock;

        if ($this->request->getMethod() === 'post') {
            $item->fill($this->request->getPost());
            $item->stock = str_to_int($this->request->getPost('stock'));
            $item->cost = str_to_double($this->request->getPost('cost'));
            $item->price = str_to_double($this->request->getPost('price'));

            if (!$item->category_id) $item->category_id = null;
            if (!$item->supplier_id) $item->supplier_id = null;

            if ($item->name == '') {
                $errors['name'] = 'Nama produk harus diisi.';
            }

            if ($model->exists($item->name, $item->id)) {
                $errors['name'] = 'Nama produk sudah digunakan, harap gunakan nama lain.';
            }

            if (empty($errors)) {
                $this->db->transBegin();

                $model->save($item);
                if (!$item->id) {
                    $item->id = $this->db->insertID();
                }

                if ($oldStock != $item->stock) {
                    $balance = $item->stock - $oldStock;
                    $this->getStockUpdateModel()->addStockUpdate($item, $adjustmentType, $balance);
                }

                $this->db->transCommit();

                if ($addProductAfterSave == 1) {
                    return redirect()->to(base_url('products/edit/0'))->with('info', 'Berhasil disimpan.');
                }

                return redirect()->to(base_url('products'))->with('info', 'Berhasil disimpan.');
            }
        }

        return view('product/edit', [
            'duplicate' => $duplicate,
            'addProductAfterSave' => $addProductAfterSave,
            'data' => $item,
            'errors' => $errors,
            'categories' => $this->getProductCategoryModel()->getAll(),
            'suppliers' => $this->getPartyModel()->getAllSuppliers(),
        ]);
    }

    public function duplicate($id)
    {
        return $this->edit($id, true);
    }

    public function delete($id)
    {
        $model = $this->getProductModel();
        $product = $model->find($id);
        if (!$product) {
            return redirect()->to(base_url('products'))->with('warning', 'Produk tidak ditemukan.');
        }

        if ($product->active) {
            $product->active = false;
            $model->save($product);
            return redirect()->to(base_url('products'))->with('info', 'Produk telah dinonaktifkan.');
        }

        $model->delete($id);
        return redirect()->to(base_url('products'))->with('info', 'Produk telah dihapus.');
    }
}
