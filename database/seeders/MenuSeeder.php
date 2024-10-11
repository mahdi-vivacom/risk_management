<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run ()
    {
        $now         = Carbon::now ();

        $menus = [
            [
                'id'            => 1,
                'label'         => 'Dashboard',
                'serial'        => '1.00',
                'route'         => 'dashboard',
                'parent_id'     => null,
                'permission_id' => null,
                'icon'          => 'bi bi-grid',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => null,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 2,
                'label'         => 'User Access Control',
                'serial'        => '2.00',
                'route'         => null,
                'parent_id'     => null,
                'permission_id' => null,
                'icon'          => 'ri-admin-line',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => null,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 3,
                'label'         => 'User List',
                'serial'        => '2.10',
                'route'         => 'users.index',
                'parent_id'     => 2,
                'permission_id' => null,
                'icon'          => 'ri-record-circle-line',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => 1,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 4,
                'label'         => 'Menu List',
                'serial'        => '2.40',
                'route'         => 'menus.index',
                'parent_id'     => 2,
                'permission_id' => null,
                'icon'          => 'bi bi-grid',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => 1,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 5,
                'label'         => 'Configuration',
                'serial'        => '3.00',
                'route'         => null,
                'parent_id'     => null,
                'permission_id' => null,
                'icon'          => 'ri-settings-4-line',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => 1,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 6,
                'label'         => 'Country List',
                'serial'        => '3.10',
                'route'         => 'countries.index',
                'parent_id'     => 5,
                'permission_id' => null,
                'icon'          => 'bi bi-grid',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => 1,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 7,
                'label'         => 'Skill List',
                'serial'        => '3.20',
                'route'         => 'skills.index',
                'parent_id'     => 5,
                'permission_id' => null,
                'icon'          => 'bi bi-grid',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => 1,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 8,
                'label'         => 'Role List',
                'serial'        => '2.20',
                'route'         => 'roles.index',
                'parent_id'     => 2,
                'permission_id' => null,
                'icon'          => 'ri-admin-line',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => 1,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 9,
                'label'         => 'Permission List',
                'serial'        => '2.30',
                'route'         => 'permissions.index',
                'parent_id'     => 2,
                'permission_id' => null,
                'icon'          => 'ri-admin-line',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => null,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
            [
                'id'            => 10,
                'label'         => 'Country Area List',
                'serial'        => '3.40',
                'route'         => 'country-areas.index',
                'parent_id'     => 5,
                'permission_id' => null,
                'icon'          => 'ri-record-circle-line',
                'status'        => 1,
                'created_by'    => 1,
                'updated_by'    => 1,
                'deleted_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
                'deleted_at'    => null,
            ],
        ];

        $permissions = Permission::all ()->keyBy ( 'name' );

        foreach ( $menus as &$menu ) {
            if ( $menu[ 'route' ] && isset ( $permissions[ $menu[ 'route' ] ] ) ) {
                $menu[ 'label' ]         = $permissions[ $menu[ 'route' ] ]->display_name;
                $menu[ 'permission_id' ] = $permissions[ $menu[ 'route' ] ]->id;
            }
        }

        unset ( $menu );

        Menu::insertOrIgnore ( $menus );

    }
}
