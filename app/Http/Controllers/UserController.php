<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasPermission:users.index')->only('index');
        $this->middleware('hasPermission:users.store')->only('store');
        $this->middleware('hasPermission:users.update')->only(['show', 'update']);
        $this->middleware('hasPermission:users.delete')->only('destroy');
        $this->middleware('hasPermission:users.update_permissions')->only(['getPermissionsFromUserAndAllPermissions', 'updatePermissions']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        return User::search($search)->orderBy('names', 'asc')->paginate(8);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'names' => 'required|max:60',
            'username' => 'required|max:40|unique:users',
            'password' => 'required|max:20',
        ], [], [
            'names' => 'Nombres',
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'names' => 'required|max:60',
            'username' => 'required|max:40|unique:users,username,' . $user->id,
            'password' => 'nullable|max:20',
        ], [], [
            'names' => 'Nombres',
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
        ]);

        if ($request->password) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPermissionsFromUserAndAllPermissions(User $user)
    {
        return [
            'permissionsFromUser' => $user->permissions,
            'allPermissions' => Permission::orderBy('id', 'asc')->get()
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePermissions(Request $request, User $user)
    {
        $data = $request->validate([
            'permissionsFromUser' => 'array',
            'permissionsFromUser.*.id' => 'required|exists:permissions,id'
        ]);

        $idsFromPermission = collect($data['permissionsFromUser'])->pluck('id');

        $user->permissions()->sync($idsFromPermission);
    }
}
