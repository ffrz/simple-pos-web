<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $items = $this->getUserModel()->getAll();

        return view('user/index', [
            'items' => $items,
        ]);
    }

    public function edit($id)
    {
        $model = $this->getUserModel();
        if ($id == 0) {
            $item = new User();
        } else {
            $item = $model->find($id);
            if (!$item) {
                return redirect()->to(base_url('users'))->with('warning', 'Pengguna tidak ditemukan.');
            }
        }

        if ($item->username == 'admin') {
            return redirect()->to(base_url('users'))->with('error', 'Akun ini tidak dapat diubah.');
        }

        $errors = [];

        if ($this->request->getMethod() === 'post') {
            if (!$id) {
                // username tidak boleh diganti
                $item->username = trim($this->request->getPost('username'));
            }

            $item->fullname = trim($this->request->getPost('fullname'));
            $item->password = $this->request->getPost('password');
            $item->is_admin = (int)$this->request->getPost('is_admin');
            $item->active = (int)$this->request->getPost('active');
            $item->group_id = (int)$this->request->getPost('group_id');

            if ($item->username == '') {
                $errors['username'] = 'Username harus diisi.';
            } else if ($model->exists($item->username, $item->id)) {
                $errors['username'] = 'Username sudah digunakan, harap gunakan nama lain.';
            } else if ($item->fullname == '') {
                $errors['fullname'] = 'Nama lengkap harus diisi.';
            } else if (!$item->id) {
                if ($item->password == '') {
                    $errors['password'] = 'Kata sandi harus diisi.';
                } else {
                    $item->password = sha1($item->password);
                }
            } else if ($item->password != '') {
                $item->password = sha1($item->password);
            }

            if (empty($errors)) {
                $model->save($item);
                return redirect()->to(base_url('users'))->with('info', 'Berhasil disimpan.');
            }
        } else {
            $item->password = '';
        }

        return view('user/edit', [
            'data' => $item,
            'userGroups' => $this->getUserGroupModel()->getAll(),
            'errors' => $errors,
        ]);
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
            'name.required' => 'Nama grup harus diisi.',
            'name.unique' => 'Nama grup sudah digunakan.',
            'name.max' => 'Nama grup terlalu panjang, maksimal 100 karakter.',
            'password.sometimes' => 'Kata sandi kadang-kadang.',
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

    public function delete($id)
    {
        $model = $this->getUserModel();
        $user = $model->find($id);

        if ($user->username == 'admin') {
            return redirect()->to(base_url('users'))
                ->with('error', 'Akun <b>' . esc($user->username) . '</b> tidak dapat dihapus.');
        } else if ($user->id == current_user()->id) {
            return redirect()->to(base_url('users'))
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($this->request->getMethod() == 'post') {
            $user->active = 0;
            $model->save($user);
            if ($model->delete($user->id)) {
                return redirect()->to(base_url('users'))->with('info', 'Pengguna ' . esc($user->username) . ' telah dihapus.');
            }

            return redirect()->to(base_url('users'))->with('info', 'Pengguna telah dinonaktifkan.');
        }

        return view('user/delete', [
            'data' => $user
        ]);
    }
}
