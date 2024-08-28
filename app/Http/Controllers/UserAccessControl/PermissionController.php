<?php

namespace App\Http\Controllers\UserAccessControl;

use App\DataTables\PermissionDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    protected $index;
    protected $indexRoute;

    function __construct ()
    {
        $this->index      = 'Permission';
        $this->indexRoute = 'permissions';
    }

    public function index ( PermissionDataTable $dataTable )
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
        ];
        return view ( 'backend.' . $this->index . '.create', $data );
    }

    public function store ( PermissionRequest $request )
    {
        Permission::create ( [ 
            'name'         => $request->input ( 'name' ),
            'guard_name'   => 'web',
            'display_name' => $request->input ( 'display_name' ),
            'module_name'  => $request->input ( 'module_name' ),
        ] );
        return redirect ()->route ( $this->indexRoute.'.index' )->with ( 'success', trans ( 'admin_fields.permission_create_message' ) );
    }

    public function show ( Permission $permission )
    {
        return $permission;
    }

    public function edit ( Permission $permission )
    {
        $data = [ 
            'title'      => 'Edit ' . $this->index,
            'permission' => $permission,
            'route'      => $this->indexRoute,
        ];
        return view ( 'backend.' . $this->index . '.edit', $data );
    }

    public function update ( Request $request, Permission $permission )
    {
        $permission->display_name = $request->input ( 'display_name' );
        $permission->module_name  = $request->input ( 'module_name' );
        if ( $permission->save () ) {
            $type = 'success';
            $msg  = $request->display_name . " " . trans ( 'admin_fields.permission_update_message' );
        } else {
            $type = 'warning';
            $msg  = trans ( 'admin_fields.permission_not_updated' );
        }
        return redirect ()->route ( $this->indexRoute.'.index' )->with ( $type, $msg );
    }

    public function destroy ( Permission $permission )
    {
        $permission->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [ 
                'type'    => 'success',
                'message' => $this->index . ' ' . trans ( 'admin_fields.data_delete_message' ),
            ] );
        }
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans ( 'admin_fields.data_delete_message' ) );
    }

}
