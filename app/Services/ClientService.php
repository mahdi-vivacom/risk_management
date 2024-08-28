<?php


namespace App\Services;

use App\Models\Client;


class ClientService
{
    public function generateUniqueReferralCode ()
    {
        $code = strtoupper ( substr ( md5 ( uniqid () ), 0, 6 ) );

        if ( Client::where ( 'referral_code', $code )->exists () )
            return $this->generateUniqueReferralCode ();
        return $code;
    }

    public function profile_image_upload ( $image )
    {
        $extension = $image->getClientOriginalExtension ();
        $imagename = pathinfo ( $image->getClientOriginalName (), PATHINFO_FILENAME );
        $imagename = str_replace ( ' ', '_', $imagename );
        $imagename = substr ( $imagename, 0, 20 );
        $imagename = $imagename . round ( microtime ( true ) * 10 ) . '.' . $extension;
        $imageUrl  = '/backend/client/profile/' . $imagename;
        $image->move ( 'backend/client/profile/', $imagename );
        return $imageUrl;
    }

}