<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CancelReason;
use App\Models\CmsPage;
use App\Models\CustomerSupport;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Throwable;

class CommonController extends Controller
{
    public function customer_support ( Request $request )
    {
        try {
            $request->validate ( [ 
                'name'    => [ 'required' ],
                'email'   => [ 'required', 'email' ],
                'phone'   => [ 'required', 'regex:/^[0-9+]+$/' ],
                'message' => [ 'required' ],
            ] );

            if ( strpos ( $request->route ()->getName (), 'client' ) !== false )
                $type = 1;
            else
                $type = 2;

            CustomerSupport::create ( [ 
                'application' => $type,
                'email'       => $request->email,
                'name'        => $request->name,
                'phone'       => $request->phone,
                'message'     => $request->message,
            ] );

            return response ()->json ( [ 
                'message' => trans ( 'api.message67' ),
                'data'    => null,
            ], 200 );
        } catch ( Throwable $th ) {

            return response ()->json ( [ 
                'message' => $th->getMessage (),
                'data'    => null,
            ], 400 );
        }
    }

    public function cancel_reason ( Request $request )
    {
        try {
            $request->validate ( [ 
                'booking_id' => [ 
                    'required',
                    'integer',
                    Rule::exists ( 'bookings', 'id' )->where ( function ($query) {
                        $query->whereIn ( 'status', [ 2 ] );
                    } ),
                ],
            ] );

            if ( strpos ( $request->route ()->getName (), 'client' ) !== false )
                $type = 1;
            else
                $type = 2;

            $data = CancelReason::where ( 'reason_for', $type )
                ->where ( 'status', 1 )
                ->get ();

            return response ()->json ( [ 
                'message' => trans ( 'api.message65' ),
                'data'    => $data,
            ], 200 );
        } catch ( Throwable $th ) {

            return response ()->json ( [ 
                'message' => $th->getMessage (),
                'data'    => null,
            ], 400 );
        }
    }

    public function cms_page ( Request $request )
    {
        try {
            $request->validate ( [ 
                'slug'       => [ 'required', 'exists:cms_pages,slug' ],
                'country_id' => [ 'required_if:slug,terms_&_conditions' ],
            ] );

            if ( strpos ( $request->route ()->getName (), 'client' ) !== false )
                $type = 1;
            else
                $type = 2;

            $data = CmsPage::select ( 'title', 'content' )
                ->where ( 'slug', $request->slug )
                ->when ( $request->filled ( 'country_id' ), function ($query) use ($request) {
                    return $query->where ( 'country_id', $request->country_id );
                } )
                ->where ( 'application', $type )
                ->where ( 'status', 1 )
                ->first ();

            return response ()->json ( [ 
                'message' => trans ( 'api.message66' ),
                'data'    => $data,
            ], 200 );
        } catch ( Throwable $th ) {

            return response ()->json ( [ 
                'message' => $th->getMessage (),
                'data'    => null,
            ], 400 );
        }
    }


}