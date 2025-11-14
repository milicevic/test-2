<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::paginate(10);
        $users = User::all();
        return view('permissions.index', compact('permissions', 'users'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function store(Request $request)
    {

        Permission::create([
            'name'     => $request->name,
            'label'    => $request->label,
        ]);

        return redirect()->route('permissions.index');
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->name  = $request->name;
        $permission->label = $request->label;

        $permission->save();

        return redirect()->route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index');
    }
        public function getUsers(Permission $permission)
    {
        $users = $permission->users()->paginate(10);

        return view('permissions.partials.user_list_modal', compact('users'));
    }
    public function assignUsers(Permission $permission)
    {
        $users = User::orderBy('name')->paginate(5);
        $permission->load('users');

        return view('permissions.assign', compact('permission', 'users'));
    }

    public function saveUsersAssignment(Request $request, Permission $permission)
    {
        $userIds = $request->input('users', []);
        $permission->users()->sync($userIds);

        return redirect()->route('permissions.index')
                         ->with('success', 'Users successfully assigned to the permission: ' . ($permission->label ?? $permission->name));
    }
}
