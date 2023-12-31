<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function _logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function login()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'ID Pengguna harus diisi.',
            'password.required' => 'Kata sandi harus diisi.',
        ]);

        if ($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator);

        $error = '';
        if (!Auth::attempt($request->only(['username', 'password']))) {
            $error = 'Username atau password salah!';
        } else if (!Auth::user()->is_active) {
            $error = 'Akun anda tidak aktif. Silahkan hubungi administrator!';
            $this->_logout($request);
        } else {
            $request->session()->regenerate();
            return redirect('/admin/dashboard');
        }

        return redirect()->back()->withInput()->with('error', $error);
    }

    public function logout(Request $request)
    {
        $this->_logout($request);
        return redirect('/admin/login');
    }
}
