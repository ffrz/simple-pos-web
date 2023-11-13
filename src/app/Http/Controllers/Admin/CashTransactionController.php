<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\CashTransactionCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CashTransactionController extends Controller
{
    const INDEX_URL = 'admin/cash-transactions';

    public function index(Request $request)
    {
        $filter = new \stdClass;
        $filter->year = (int)$request->year;
        $filter->month = (int)$request->month;
        $filter->account_id = (int)$request->account_id;
        $filter->category_id = (int)$request->category_id;

        if ($filter->year == 0)
            $filter->year = date('Y');

        if ($filter->month < 1 || $filter->month > 12)
            $filter->month = date('m');

        $query = CashTransaction::with('account', 'category');
        $query->whereYear('datetime', '=', $filter->year);
        $query->whereMonth('datetime', '=', $filter->month);

        if ($filter->account_id != 0)
            $query->where('account_id', '=', $filter->account_id);

        if ($filter->category_id != 0)
            $query->where('category_id', '=', $filter->category_id);

        $query->orderBy('datetime', 'desc');
        $items = $query->get();

        $accounts = CashAccount::all();
        $categories = CashTransactionCategory::all();

        return view('admin.cash-transaction.index', compact('items', 'filter', 'accounts', 'categories'));
    }

    public function edit(Request $request, $id)
    {
        if ($id == 0) {
            $item = new CashTransaction();
            $item->datetime = date('Y-m-d H:i:s');
        } else {
            $item = CashTransaction::find($id);
            if (!$item) {
                return redirect(self::INDEX_URL)->with('warning', 'Transaksi tidak ditemukan.');
            }
        }

        $oldAmount = $item->amount;

        if ($request->method() === 'POST') {
            $data = $request->all();

            $validator = Validator::make($data, [
                'description' => 'required',
                'account_id' => 'required',
                'category_id' => 'required',
                'datetime' => 'required',
                'amount' => 'required',
            ], [
                'account_id.required' => 'Akun harus dipilih.',
                'category_id.required' => 'Kategori harus dipilih.',
                'datetime.required' => 'Tanggal harus diisi.',
                'amount.required' => 'Jumlah uang harus dipilih.',
                'description.required' => 'Deskripsi harus diisi.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $item->fill($data);
            $item->datetime = datetime_from_input($item->datetime);
            $item->amount = (float)$item->amount;
            $item->account_id = (int)$item->account_id;

            if ($data['type'] = 'expense')
                $item->amount *= -1;

            try {
                DB::beginTransaction();

                if ($oldAmount != $item->amount) {
                    $account = CashAccount::find($item->account_id);
                    $oldBalance = $account->balance;
                    $account->balance = $oldBalance - ($oldAmount - $item->amount);
                    $account->save();
                }

                $item->save();

                DB::commit();
            } catch (Exception $ex) {
                DB::rollBack();
                return redirect(self::INDEX_URL)->with('error', 'Transaksi gagal disimpan.');
            }
            return redirect(self::INDEX_URL)->with('info', 'Transaksi telah disimpan.');
        }

        $accounts = CashAccount::all();
        $categories = CashTransactionCategory::all();

        return view('admin.cash-transaction.edit', compact('item', 'categories', 'accounts'));
    }

    public function delete($id)
    {
        $item = CashTransaction::find($id);
        if (!$item) {
            return redirect(self::INDEX_URL)->with('warning', 'Transaksi tidak ditemukan.');
        }

        $account = CashAccount::find($item->account_id);

        try {
            DB::beginTransaction();

            $account->balance += -$item->amount;
            $account->save();
            
            $item->delete();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect(self::INDEX_URL)->with('error', 'Transaksi tidak dapat dihapus.');
        }

        return redirect(self::INDEX_URL)->with('info', 'Transaksi telah dihapus.');
    }
}
