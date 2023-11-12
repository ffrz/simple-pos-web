<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function edit(Request $request)
    {
        $data = [
            'store_name' => Setting::value('app.store_name', 'Toko Saya'),
            'store_address' => Setting::value('app.store_address', '')
        ];
        return view('admin.setting.edit', compact('data'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_name' => 'required'
        ], [
            'store_name.required' => 'Nama Toko harus diisi.'
        ]);

        if ($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator);

        DB::beginTransaction();
        Setting::setValue('app.store_name', $request->post('store_name', ''));
        Setting::setValue('app.store_address', $request->post('store_address', ''));
        DB::commit();

        return redirect('admin/settings')->with('info', 'Pengaturan telah disimpan.');
    }
}
