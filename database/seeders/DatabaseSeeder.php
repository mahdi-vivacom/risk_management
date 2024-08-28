<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run ()
    {
        $user = User::firstOrCreate (
            [ 'email' => 'systemadmin@gmail.com' ],
            [ 
                'name'     => 'System Admin User',
                'password' => Hash::make ( '12345678' ),
            ],
        );
        $user->update ( [ 'password_set_by_user_id' => $user->id ] );

        $role = Role::firstOrCreate (
            [ 'name' => 'systemadmin' ],
            [ 
                'guard_name'   => 'web',
                'display_name' => 'System Admin',
            ],
        );

        $user->assignRole ( [ $role->id ] );

        $array = [ 
            [ 
                'name'         => 'superadmin',
                'guard_name'   => 'web',
                'display_name' => 'Super Admin',
                'created_at'   => Carbon::now (),
                'updated_at'   => Carbon::now (),
            ],
            [ 
                'name'         => 'admin',
                'guard_name'   => 'web',
                'display_name' => 'Admin',
                'created_at'   => Carbon::now (),
                'updated_at'   => Carbon::now (),
            ],
        ];
        Role::insertOrIgnore ( $array );

    }
}
