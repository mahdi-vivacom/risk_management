<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        app ()[ PermissionRegistrar::class]->forgetCachedPermissions ();

        // Load web routes file
        $routesPath = base_path ( 'routes/web.php' );
        require $routesPath;

        // Get all registered routes
        $routesCollection = Route::getRoutes ();

        // Initialize an empty array to store web routes
        $webRoutes = [];

        // Iterate through each route and filter those belonging to the web middleware group
        foreach ( $routesCollection as $route ) {
            if ( !in_array ( 'web', $route->middleware () ) ) {
                $webRoutes[] = $route;
            }
        }

        foreach ( $webRoutes as $route ) {
            // Get route name
            $routeName = $route->getName ();

            // Exclude routes without names
            if ( $routeName ) {
                // Extract module name from route name
                $moduleDelimiterPosition = strpos ( $routeName, '.' );
                $moduleName              = $moduleDelimiterPosition !== false ? substr ( $routeName, 0, $moduleDelimiterPosition ) : $routeName;

                // Make the module name singular
                $moduleName = Str::singular ( $moduleName );

                // Remove hyphens from the module name
                $moduleName = str_replace ( '-', ' ', $moduleName );

                // Capitalize each word in the module name
                $moduleName = ucwords ( $moduleName );

                $permissionName = str_replace ( '.', ' ', $routeName );
                $permissionName = str_replace ( '-', ' ', $permissionName );
                $permissionName = str_replace ( 'index', 'list', $permissionName );
                // $permissionName = Str::singular ( $permissionName );
                $permissionName = ucwords ( $permissionName );

                // Use updateOrCreate to handle both creation and updating
                Permission::updateOrCreate (
                    [ 'name' => $routeName ], // Attributes to check for existing record
                    [ 
                        'guard_name'   => 'web',
                        'display_name' => $permissionName,
                        'module_name'  => $moduleName,
                    ],
                );
            }
        }

    }
}
