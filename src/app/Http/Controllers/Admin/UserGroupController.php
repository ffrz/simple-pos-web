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
    private const INDEX_URL = '/admin/user-groups';

    public function index()
    {
        $items = UserGroup::all();
        return view('admin.user-group.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $group = $id ? UserGroup::find($id) : new UserGroup();
        if (!$group)
            return redirect(self::INDEX_URL)->with('warning', 'Grup Pengguna tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:user_groups,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama grup harus diisi.',
                'name.unique' => 'Nama grup sudah digunakan.',
                'name.max' => 'Nama grup terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $group->fill($request->all());
            $group->save();

            return redirect(self::INDEX_URL)->with('info', 'Grup pengguna telah disimpan.');
        }

        return view('admin.user-group.edit', compact('group'));
    }

    public function delete($id)
    {
        if (!$userGroup = UserGroup::find($id))
            $message = 'Grup pengguna tidak ditemukan.';
        else if ($userGroup->delete($id))
            $message = 'Grup pengguna ' . $userGroup->name . ' telah dihapus.';

        return redirect(self::INDEX_URL)->with('info', $message);
    }
}
