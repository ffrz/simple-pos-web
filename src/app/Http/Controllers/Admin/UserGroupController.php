<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserGroupController extends Controller
{
    public function index()
    {
        $items = UserGroup::all();
        return view('admin.user-group.index', compact('items'));
    }

    public function edit($id = 0)
    {
        $data = [
            'id' => 0,
            'name' => '',
            'description' => '',
        ];

        if ($id) {
            $data = UserGroup::find($id);
            if (!$data) {
                return redirect('/admin/user-groups')->with('warning', 'Grup Pengguna tidak ditemukan.');
            }
        }

        return view('admin.user-group.edit', compact('data'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:user_groups,name,' . $request->id . '|max:100',
        ], [
            'name.required' => 'Nama grup harus diisi.',
            'name.unique' => 'Nama grup sudah digunakan.',
            'name.max' => 'Nama grup terlalu panjang, maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if (!$request->id) {
            UserGroup::create($data);
        } else {
            $group = UserGroup::find($request->id);
            $group->update($data);
        }

        return redirect('/admin/user-groups')->with('info', 'Grup pengguna telah disimpan.');
    }

    public function delete($id)
    {
        $userGroup = UserGroup::find($id);

        if (!$userGroup) {
            $message = 'Grup pengguna tidak ditemukan.';
        } else if ($userGroup->delete($id)) {
            $message = 'Grup pengguna ' . $userGroup->name . ' telah dihapus.';
        }

        return redirect('/admin/user-groups')->with('info', $message);
    }
}
