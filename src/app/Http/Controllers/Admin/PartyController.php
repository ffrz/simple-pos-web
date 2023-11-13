<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class PartyController extends Controller
{
    protected $type = 0;
    protected $view_path = '';
    protected $index_url = '';

    public function index()
    {
        $items = Party::where('type', '=', $this->type)->get();
        return view($this->view_path . '.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        if ($id == 0) {
            $item = new Party();
            $item->active = true;
        } else {
            $item = Party::find($id);
            if (!$item) {
                return redirect($this->index_url)
                    ->with('warning', 'Item tidak ditemukan.');
            }
        }

        if ($request->method() === 'POST') {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required',
            ], [
                'name.required' => 'Nama harus diisi.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $item->fill($data);
            $item->type = $this->type;
            $item->save();

            return redirect($this->index_url)->with('info', 'Berhasil disimpan.');
        }

        return view($this->view_path . '.edit', compact('item'));
    }

    public function delete($id)
    {
        $party = Party::find($id);
        if ($party->type != $this->type) {
            return redirect($this->index_url)->with('warning', 'Rekaman tidak ditemukan.');
        }

        $party->delete();

        return redirect($this->index_url)->with('info', 'Rekaman telah dihapus.');
    }
}
