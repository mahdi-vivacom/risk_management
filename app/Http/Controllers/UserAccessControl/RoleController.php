<?php

namespace App\Http\Controllers\UserAccessControl;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $index;
    protected $indexRoute;

    function __construct ()
    {
        $this->index      = 'Role';
        $this->indexRoute = 'roles';
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.index', ['only' => ['index']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.create', ['only' => ['create']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.store', ['only' => ['store']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.show', ['only' => ['show']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.edit', ['only' => ['edit']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.update', ['only' => ['update']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.destroy', ['only' => ['destroy']]);
    }

    public function index ( RoleDataTable $dataTable )
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render ( 'backend.common.index', $data );
    }

    public function create ()
    {
        $permissions    = [];
        $roleManagement = Permission::all ();
        foreach ( $roleManagement as $key => $value ) {
            $permissions[ $value[ 'module_name' ] ][ $value[ 'id' ] ] = $value[ 'display_name' ];
        }
        $data = [
            'title'       => 'Create ' . $this->index,
            'permissions' => $permissions,
            'route'       => $this->indexRoute,
        ];
        return view ( 'backend.' . $this->index . '.create', $data );
    }

    public function store ( RoleRequest $request )
    {
        $role = Role::create ( [
            'name'         => $request->input ( 'name' ),
            'display_name' => $request->input ( 'display_name' ),
        ] );
        $role->syncPermissions ( $request->input ( 'permission' ) );
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', trans ( 'admin_fields.role_create_message' ) );
    }

    public function show ( Role $role )
    {
        return $role;
    }

    public function edit ( Role $role )
    {
        $permissions      = [];
        $roleManagement   = Permission::all ();
        $role_permissions = $role->getAllPermissions ()->pluck ( 'id', 'id' )->toArray ();
        foreach ( $roleManagement as $key => $value ) {
            $permissions[ $value[ 'module_name' ] ][ $value[ 'id' ] ] = $value[ 'display_name' ];
        }
        $data = [
            'title'            => 'Edit ' . $this->index,
            'role'             => $role,
            'permissions'      => $permissions,
            'role_permissions' => $role_permissions,
            'route'            => $this->indexRoute,
        ];
        return view ( 'backend.' . $this->index . '.edit', $data );
    }

    public function update ( Request $request, Role $role )
    {
        $this->validate ( $request, [
            'display_name' => 'required',
        ] );
        $role->display_name = $request->input ( 'display_name' );
        if ( $role->save () ) {
            $role->syncPermissions ( $request->input ( 'permission' ) );
            $type = 'success';
            $msg  = $request->display_name . ' ' . trans ( 'admin_fields.role_update_message' );
        } else {
            $type = 'warning';
            $msg  = trans ( 'admin_fields.role_not_update_message' );
        }
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( $type, $msg );
    }

    public function destroy ( Role $role )
    {
        $role->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans ( 'admin_fields.data_delete_message' ),
            ] );
        }
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans ( 'admin_fields.data_delete_message' ) );
    }

}
