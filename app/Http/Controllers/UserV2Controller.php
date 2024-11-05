<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserV2Controller extends Controller
{
    public function index(Request $request)
    {
        return inertia('user/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $search = $request->get('filter', '');

        $q = User::query();
        $q->orderBy($orderBy, $orderType);
        if (!empty($search)) {
            $q->where('name', 'like', '%' . $search . '%');
            $q->orWhere('email', 'like', '%' . $search . '%');
        }

        $users = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($users);
    }

    public function editor($id = 0)
    {
        $user = $id ? User::findOrFail($id) : new User();

        if (!$id) {
            $user->active = true;
            $user->admin = true;
        } else if ($user == Auth::user()) {
            return redirect('/user')->with('warning', 'Can not edit current user.');
        }

        return inertia('user/Editor', [
            'data' => $user,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email,' . $request->id . '|min:3|max:100',
            'password' => 'required|min:5|max:40',
        ];
        $user = null;
        $message = '';
        $fields = ['name', 'email', 'admin', 'active'];
        $password = $request->get('password');

        if (!$request->id) {
            $request->validate($rules);
            $user = new User();
            $message = 'New user created.';
        } else {
            if (empty($request->get('password'))) {
                unset($rules['password']);
                unset($fields['password']);
            }
            $request->validate($rules);
            $user = User::findOrFail($request->id);
            $message = 'User updated.';
        }

        if (!empty($password)) {
            $user->password = Hash::make($password);
        }
        $user->fill($request->only($fields));
        $user->save();

        return redirect('/user-v2')->with('success', $message);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == Auth::user()->id) {
            return response()->json([
                'message' => 'Cannot delete current user!'
            ], 409);
        }

        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted!'
        ]);
    }
}
