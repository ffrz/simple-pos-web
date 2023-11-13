<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashTransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashTransactionCategoryController extends Controller
{
    const INDEX_URL = 'admin/cash-transaction-categories';

    public function index()
    {
        $items = CashTransactionCategory::all();
        return view('admin.cash-transaction-category.index', compact('items'));
    }

    public function edit(Request $request, $id)
    {
        if ($id == 0) {
            $item = new CashTransactionCategory();
        } else {
            $item = CashTransactionCategory::find($id);
            if (!$item) {
                return redirect(self::INDEX_URL)->with('warning', 'Kategori tidak ditemukan.');
            }
        }

        if ($request->method() === 'POST') {
            $data = $request->all();
            
            $validator = Validator::make($data, [
                'name' => 'required|unique:cash_transaction_categories,name,' . $id . '|max:100',
            ], [
                'name.required' => 'Nama kategori harus diisi.',
                'name.unique' => 'Nama kategori ini sudah pernah digunakan.',
                'name.max' => 'Nama kategori terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $item->fill($data);
            $item->save();

            return redirect(self::INDEX_URL)->with('info', 'Kategori telah disimpan.');
        }

        return view('admin.cash-transaction-category.edit', compact('item'));
    }

    public function delete($id)
    {
        $item = CashTransactionCategory::find($id);
        if (!$item) {
            return redirect(self::INDEX_URL)->with('warning', 'Kategori tidak ditemukan.');
        }

        $item->delete();

        return redirect(self::INDEX_URL)->with('info', 'Kategori telah dihapus.');
    }
}
