<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('permissions')->paginate(10);


        // Pass the users to the view
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function edit(User $user)
    {
        $availablePermissions = Permission::all();
        return view('users.edit', compact('user', 'availablePermissions'));
    }

    public function store(Request $request)
    {

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->permissions()->sync($request->input('permissions', []));

        $user->save();

        return redirect()->route('users.index');
    }
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
