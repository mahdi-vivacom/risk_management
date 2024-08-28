<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function storeUser ( $data )
    {
        DB::beginTransaction ();
        $user = User::create ( [ 
            'name'          => $data->name,
            'email'         => $data->email,
            'phone_number'  => $data->phone_number,
            'profile_image' => $data->profile_image,
            'password'      => Hash::make ( $data->password ),
        ] );
        $user->assignRole ( $data->role );
        DB::commit ();
    }

    public function profile_image_upload ( $image )
    {
        $extension = $image->getClientOriginalExtension ();
        $imagename = pathinfo ( $image->getClientOriginalName (), PATHINFO_FILENAME );
        $imagename = str_replace ( ' ', '_', $imagename );
        $imagename = substr ( $imagename, 0, 20 );
        $imagename = $imagename . round ( microtime ( true ) * 10 ) . '.' . $extension;
        $imageUrl  = '/backend/user/profile/' . $imagename;
        $image->move ( 'backend/user/profile/', $imagename );
        return $imageUrl;
    }

}
