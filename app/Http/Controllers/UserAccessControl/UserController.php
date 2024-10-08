<?php

namespace App\Http\Controllers\UserAccessControl;

use App\DataTables\UserDataTable;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;


class UserController extends Controller
{
    protected $index;
    protected $indexRoute;
    private $userService;

    public function __construct ( UserService $userService )
    {
        $this->index       = 'user';
        $this->indexRoute  = 'users';
        $this->userService = $userService;
    }

    public function index ( UserDataTable $dataTable )
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render ( 'backend.common.index', $data );
    }

    public function create ()
    {
        $data = [
            'title' => 'Create ' . $this->index,
            'route' => $this->indexRoute,
            'roles' => Role::orderBy ( 'id', 'DESC' )->where ( 'name', '!=', 'systemadmin' )->get (),
        ];
        return view ( 'backend.' . $this->index . '.create', $data );
    }

    public function store ( Request $request )
    {
        $request->validate ( [
            'role'     => 'required',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:255',
        ] );
        if ( $request->hasFile ( 'profile_image' ) ) {
            $request->profile_image = $this->userService->profile_image_upload ( $request->hasFile ( 'profile_image' ) );
        }
        $this->userService->storeUser ( $request );
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans ( 'admin_fields.data_store_message' ) );
    }

    public function show ( User $user )
    {
        return $user;
    }

    public function edit ( User $user )
    {
        $data = [
            'title' => 'Edit ' . $this->index,
            'route' => $this->indexRoute,
            'user'  => $user,
            'roles' => Role::orderBy ( 'id', 'DESC' )->where ( 'name', '!=', 'systemadmin' )->get (),
        ];
        return view ( 'backend.' . $this->index . '.edit', $data );
    }

    public function update ( Request $request, User $user )
    {
        $request->validate ( [
            'name' => 'required',
            'role' => 'required',
        ] );

        if ( $request->has ( 'password' ) ) {
            $user->update ( [ 'password' => Hash::make ( $request->password ) ] );
        }

        if ( $request->hasFile ( 'profile_image' ) ) {
            $imageUrl = $this->userService->profile_image_upload ( $request->hasFile ( 'profile_image' ) );
            $user->update ( [ 'profile_image' => $imageUrl ] );
        }

        $user->update ( [
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
        ] );

        $user->syncRoles ( $request->role );

        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans ( 'admin_fields.data_update_message' ) );
    }

    public function destroy ( User $user )
    {
        $user->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans ( 'admin_fields.data_delete_message' ),
            ] );
        }
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans ( 'admin_fields.data_delete_message' ) );
    }

    public function edit_permission ( $user_id )
    {
        $title            = $this->index . " Edit Permissions";
        $permissions      = [];
        $route            = $this->indexRoute;
        $roleManagement   = Permission::all ();
        $user             = User::find ( $user_id );
        $role_permissions = $user->getAllPermissions ()->pluck ( 'id', 'id' )->toArray ();
        foreach ( $roleManagement as $key => $value ) {
            $permissions[ $value[ 'module_name' ] ][ $value[ 'id' ] ] = $value[ 'display_name' ];
        }
        return view ( 'backend.' . $this->index . '.permission', compact ( 'title', 'route', 'user_id', 'permissions', 'role_permissions' ) );
    }

    public function update_permission ( Request $request )
    {
        $user = User::find ( $request->id );
        $user->syncPermissions ( $request->input ( 'permission' ) );
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans ( 'admin_fields.permission_update_message' ) );
    }

    public function status ( Request $request )
    {
        User::where ( 'id', $request->id )->update ( [ 'status' => $request->status ] );
        try {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $request->status == 1 ? trans ( 'admin_fields.active_status' ) : trans ( 'admin_fields.inactive_status' ),
            ] );
        } catch ( Throwable $th ) {
            return response ()->json ( [
                'type'    => 'error',
                'message' => $th->getMessage (),
            ] );
        }
    }

}
