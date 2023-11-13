<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashAccountController extends Controller
{
    const INDEX_URL = 'admin/cash-accounts';

    public function index()
    {
        $items = CashAccount::all();
        return view('admin.cash-account.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = $id ? CashAccount::find($id) : new CashAccount();
        if (!$item) {
            return redirect(self::INDEX_URL)->with('warning', 'Akun tidak ditemukan.');
        }

        if ($request->method() === 'POST') {
            $oldBalance = $item->balance;

            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required|unique:cash_accounts,name,' . $id . '|max:100',
                'type' => 'required',
            ], [
                'name.required' => 'Nama akun harus diisi.',
                'name.unique' => 'Nama akun ini sudah pernah digunakan.',
                'name.max' => 'Nama akun terlalu panjang, maksimal 100 karakter.',
                'type.required' => 'Silahkan pilih jenis akun.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $is_new = !$item->id;

            $item->fill($data);
            $item->balance = (int)$item->balance;
            $item->save();

            if ($item->balance != $oldBalance) {
                $amount = $item->balance - $oldBalance;
                $transaction = new CashTransaction();
                $transaction->account_id = $item->id;
                $transaction->datetime = date('Y-m-d H:i:s');
                $transaction->amount = $amount;
                $transaction->description = $is_new ? 'Saldo Awal' :'Penyesuaian Saldo';
                $transaction->category_id = $is_new ? 1 : 2; // TODO: ambil dari setting untuk kode penyesuaian saldo
                $transaction->save();
            }

            return redirect(self::INDEX_URL)->with('info', 'Akun telah disimpan.');
        }

        return view('admin.cash-account.edit', compact('item'));
    }

    public function delete($id)
    {
        $item = CashAccount::find($id);
        if (!$item) {
            return redirect(self::INDEX_URL)->with('warning', 'Akun tidak ditemukan.');
        }
        $item->forceDelete();
        return redirect(self::INDEX_URL)->with('warning', 'Akun tidak ditemukan.');
    }
}
