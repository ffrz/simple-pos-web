<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $items = User::all();
        return view('admin.user.index', compact('items'));
    }

    public function edit($id = 0)
    {
        if ($id == 0) {
            $data = new User();
        } else {
            $data = User::find($id);
            if (!$data) {
                return redirect('/admin/users')->with('warning', 'Pengguna tidak ditemukan.');
            }
        }

        if ($data->username == 'superadmin') {
            return redirect('/admin/users')->with('error', 'Akun administrator ini tidak dapat diubah.');
        }

        $groups = UserGroup::all();

        return view('admin.user.edit', compact('data', 'groups'));
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:100'
        ];

        if (!$request->id) { // nambah user baru
            $rules['username'] = 'required|unique:users,username,' . (int)$request->id . '|min:3|max:40';
        } else if (!empty($request->password)) { // update dan password diisi
            $rules['password'] = 'min:5|max:40';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama terlalu panjang, maksimal 100 karakter.',
            'username.required' => 'ID Pengguna harus diisi.',
            'username.unique' => 'ID Pengguna harus unik.',
            'username.min' => 'ID Pengguna terlalu pendek, minial 5 karakter.',
            'username.max' => 'ID Pengguna terlalu panjang, maksimal 40 karakter.',
            'password.min' => 'Kata sandi terlalu pendek, minimal 5 karakter.',
            'password.max' => 'Kata sandi terlalu panjang, maksimal 40 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = $request->all();
        if (!$request->id) {
            User::create($data);
            $message = 'Akun pengguna ' . $data['username'] . ' telah dibuat.';
        } else {
            $user = User::find($request->id);
            $user->update($data);
            $message = 'Akun pengguna ' . $data['username'] . ' telah diperbarui.';
        }

        return redirect('/admin/users')->with('info', $message);
    }

    public function profile()
    {
        if (!$user = User::find(Auth::user()->id)) {
            return redirect(url('/admin/login'));
        }

        return view('admin.user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
        ];

        if (!empty($request->post('password'))) {
            $rules['password'] = 'min:5|max:40|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama terlalu panjang, maksimal 100 karakter.',
            'password.min' => 'Kata sandi terlalu pendek, minimal 5 karakter.',
            'password.max' => 'Kata sandi terlalu panjang, maksimal 40 karakter.',
            'password.confirmed' => 'Kata sandi yang anda konfirmasi salah.',
            'password_confirmation.required' => 'Anda belum mengkonfirmasi kata sandi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = $request->only(['name', 'password']);
        $user = User::find(Auth::user()->id);
        $user->update($data);

        return redirect('/admin/users/profile')->with('info', 'Profil anda telah diperbarui.');
    }

    public function delete(Request $request, $id)
    {
        $user = User::with('group')->find($id);
        $redirect_url = '/admin/users';

        if ($user->username == 'superadmin') {
            return redirect($redirect_url)
                ->with('error', 'Akun <b>' . $user->username . '</b> tidak dapat dihapus.');
        } else if ($user->id == Auth::user()->id) {
            return redirect($redirect_url)
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($request->method() == 'POST') {
            $user->forceDelete();
            return redirect($redirect_url)
                ->with('info', 'Akun ' . $user->username . ' telah dihapus.');
        }

        return view('/admin/user/delete', compact('user'));
    }
}
