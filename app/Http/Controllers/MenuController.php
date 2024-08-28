<?php

namespace App\Http\Controllers;

use App\DataTables\MenusDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;

class MenuController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Menu';
        $this->indexRoute = 'menus';
    }
    public function index ( MenusDataTable $dataTable )
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render ( 'backend.common.index', $data );
    }

    public function create ()
    {
        $title       = 'Create ' . $this->index;
        $menus       = Menu::orderBy ( 'id', 'ASC' )->whereNull ( 'parent_id' )->get ();
        $permissions = Permission::orderBy ( 'name', 'ASC' )->get ();
        $routes      = Route::getRoutes ();

        $routeNames = [];
        foreach ( $routes as $route ) {
            $routeName = $route->getName ();
            if ( $routeName ) {
                $routeNames[] = $routeName;
            }
        }
        return view ( 'backend.menu.create', compact ( 'title', 'menus', 'permissions', 'routeNames' ) );
    }

    public function store ( Request $request )
    {
        $request->validate ( [
            'label'  => 'required|max:255',
            'serial' => 'required|numeric',
            // 'permission_id' => 'required',
            'icon'   => 'required',
        ] );
        Menu::create ( [
            'label'         => $request->label,
            'serial'        => $request->serial,
            'route'         => $request->route,
            'parent_id'     => $request->parent_id,
            'permission_id' => $request->permission_id ?? null,
            'icon'          => $request->icon,
            'created_by'    => auth ()->user ()->id,
        ] );
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_update_message') );
    }

    public function edit ( Menu $menu )
    {
        $title       = 'Edit ' . $this->index;
        $data        = $menu;
        $menus       = Menu::orderBy ( 'id', 'ASC' )->whereNull ( 'parent_id' )->get ();
        $permissions = Permission::orderBy ( 'name', 'ASC' )->get ();
        $routes      = Route::getRoutes ();

        $routeNames = [];
        foreach ( $routes as $route ) {
            $routeName = $route->getName ();
            if ( $routeName ) {
                $routeNames[] = $routeName;
            }
        }
        return view ( 'backend.menu.edit', compact ( 'title', 'data', 'menus', 'permissions', 'routeNames' ) );
    }

    public function update ( Request $request )
    {
        $request->validate ( [
            'label'  => 'required|max:255',
            'serial' => 'required|numeric',
            // 'permission_id' => 'required',
            'icon'   => 'required',
        ] );
        // dd ( $request->permission_id ?? null);

        $menu                = Menu::find ( $request->id );
        $menu->label         = $request->label;
        $menu->serial        = $request->serial;
        $menu->route         = $request->route;
        $menu->parent_id     = $request->parent_id;
        $menu->permission_id = $request->permission_id ?? null;
        $menu->icon          = $request->icon;
        $menu->updated_by    = auth ()->user ()->id;
        if ( $menu->save () ) {
            $type = 'success';
            $msg  = $this->index . ' ' . trans('admin_fields.data_update_message');
        } else {
            $type = 'warning';
            $msg  = $this->index . ' not updated.';
        }
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( $type, $msg );
    }

    public function destroy ( Menu $menu )
    {
        $menu->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_delete_message') );
    }

}
