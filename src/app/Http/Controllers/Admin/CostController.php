<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cost;
use App\Models\CostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CostController extends Controller
{
    const INDEX_URL = '/admin/costs';

    public function index(Request $request)
    {
        $filter = new \stdClass;
        $filter->year = (int)$request->year;
        $filter->month = (int)$request->month;
        
        if ($filter->year == 0)
            $filter->year = date('Y');

        if ($filter->month < 1 || $filter->month > 12)
            $filter->month = date('m');

        $query = Cost::with('category');
        $query->whereYear('date', '=', $filter->year);
        $query->whereMonth('date', '=', $filter->month);
        $items = $query->get();

        return view('admin.cost.index', compact('items', 'filter'));
    }

    public function edit(Request $request, $id)
    {
        if ($id == 0) {
            $item = new Cost();
            $item->date = date('Y-m-d');
        } else {
            $item = Cost::find($id);
            if (!$item) {
                return redirect(self::INDEX_URL)->with('warning', 'Biaya tidak ditemukan.');
            }
        }

        if ($request->method() == 'POST') {
            $data = $request->all();
            $data['amount'] = $data['amount'];
            $data['date'] = datetime_from_input($data['date']);

            $validator = Validator::make($data, [
                'description' => 'required|max:100',
                'amount' => 'required',
                'date' => 'required',
                'category_id' => 'required',
            ], [
                'description.required' => 'Deskripsi harus diisi.',
                'amount.required' => 'Jumlah biaya harus diisi.',
                'date.required' => 'Tanggal harus diisi.',
                'category_id.required' => 'Kategori harus diisi.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $item->fill($data);
            $item->save();

            return redirect(self::INDEX_URL)->with('info', 'Biaya operasional telah disimpan.');
        }

        $categories = CostCategory::all();
        return view('admin.cost.edit', compact('item', 'categories'));
    }

    public function delete($id)
    {
        $item = Cost::find($id);
        if (!$item) {
            return redirect(self::INDEX_URL)->with('warning', 'Rekaman biaya tidak ditemukan.');
        }

        $item->delete();

        return redirect(self::INDEX_URL)->with('info', 'Rekaman biaya telah dihapus.');
    }
}
