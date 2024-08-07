<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware("can:manipulate,App\User");
    }

    public function index()
    {
        $users = User::select('id', 'email', 'name', 'role')
            ->orderBy('email')
            ->get();
        return view('users.index', ['users' => $users]);
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function save(UserRequest $request)
    {
        $user = User::findOrFail($request->id);
        $user->fill($request->all())->save();
        return redirect()->action([static::class, 'index'])->with('status', "Пользователь {$user->name} исправлен");
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();
        return redirect()->action([static::class, 'index'])
            ->with("status", "Пользователь " . $name . " удален");
    }
}
