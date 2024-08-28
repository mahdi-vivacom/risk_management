<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use App\Models\WalletTransaction;
use App\Services\WaafiService;
use Throwable;

class SubscriptionController extends Controller
{
    private $waafiService;

    public function __construct ( WaafiService $waafiService )
    {
        $this->waafiService = $waafiService;
    }

    public function packages ( Request $request )
    {
        $professional = $request->user ();
        try {
            $subscriptions = Subscription::select ( 'id', 'title', 'label', 'amount', 'details', 'description', 'area_id', 'renewal', \DB::raw ( 'MAX(created_at) as latest_created_at' ) )
                ->where ( 'status', 1 )
                ->where ( 'area_id', $professional->country_area_id )
                ->groupBy ( 'id', 'area_id', 'renewal' )
                ->orderBy ( 'latest_created_at', 'desc' )
                ->get ();

            if ( $subscriptions->isNotEmpty () ) {
                $message = trans ( 'api.message34' );
                $status  = 200;
            } else {
                $message = trans ( 'api.message33' );
                $status  = 404;
            }
            return response ()->json ( [ 
                'message' => $message,
                'data'    => $subscriptions,
            ], $status );
        } catch ( Throwable $th ) {
            return response ()->json ( [ 
                'message' => $th->getMessage (),
                'data'    => null,
            ], 400 );
        }
    }

    public function add ( Request $request )
    {
        $professional = $request->user ();
        $phoneNumber  = str_replace ( "+", "", $professional->phone_number );

        try {
            $subscription = Subscription::where ( 'id', $request->package_id )
                ->where ( 'status', 1 )
                ->where ( 'area_id', $professional->country_area_id )
                ->firstOrFail ();

        } catch ( ModelNotFoundException $e ) {

            return response ()->json ( [ 
                'message' => trans ( 'api.message33' ),
                'data'    => null,
            ], 404 );
        } catch ( ValidationException $e ) {

            return response ()->json ( [ 
                'message' => $e->getMessage (),
                'data'    => null,
            ], 422 );
        } catch ( Throwable $e ) {

            return response ()->json ( [ 
                'message' => $e->getMessage (),
                'data'    => null,
            ], 400 );
        }

        if ( $subscription->renewal == 'Monthly' ) {
            $validate_till = Carbon::now ()->addMonth ()->format ( 'Y-m-d H:i:s' );
        } elseif ( $subscription->renewal == '3 Months' ) {
            $validate_till = Carbon::now ()->addMonths ( 3 )->format ( 'Y-m-d H:i:s' );
        } elseif ( $subscription->renewal == '6 Months' ) {
            $validate_till = Carbon::now ()->addMonths ( 6 )->format ( 'Y-m-d H:i:s' );
        } elseif ( $subscription->renewal == 'Weekly' ) {
            $validate_till = Carbon::now ()->addWeek ()->format ( 'Y-m-d H:i:s' );
        } elseif ( $subscription->renewal == 'Yearly' ) {
            $validate_till = Carbon::now ()->addYear ()->format ( 'Y-m-d H:i:s' );
        } else {
            $validate_till = Carbon::now ()->format ( 'Y-m-d H:i:s' );
        }

        $response = $this->waafiService->waafi_api ( $subscription->amount, $phoneNumber );

        if ( $response->status () === 200 ) {
            if ( !empty ( $response[ 'responseCode' ] ) && ( $response[ 'responseCode' ] === '2001' ) ) {

                SubscriptionHistory::where ( 'professional_id', $professional->id )
                    ->update ( [ 'status' => 0 ] );

                $subscription_history = SubscriptionHistory::create ( [ 
                    'package_id'      => $subscription->id,
                    'professional_id' => $professional->id,
                    'area_id'         => $subscription->area_id,
                    'auto_renewal'    => !empty ( $request->auto_renewal ) ? $request->auto_renewal : 0,
                    'validate_till'   => $validate_till,
                    'status'          => 1,
                ] );

                WalletTransaction::create ( [ 
                    'professional_id'  => $professional->id,
                    'transaction_type' => 1,
                    'receipt_number'   => trans ( 'api.message31' ),
                    'amount'           => $subscription->amount,
                    'description'      => trans ( 'api.message31' ),
                    'narration'        => 1,
                    'booking_id'       => null,
                ] );
                $message = trans ( 'api.message32' );

            } else {
                $subscription_history = [];
                $message              = trans ( 'api.message35' );
            }

        } else {
            $subscription_history = [];
            $message              = trans ( 'api.message35' );
        }
        return response ()->json ( [ 
            'message' => $message,
            'data'    => $subscription_history,
        ], $response->status () );
    }

    public function history ( Request $request )
    {
        $professional = $request->user ();
        $subscription = false;

        try {
            $subscriptions = SubscriptionHistory::orderBy ( 'id', 'DESC' )
                ->where ( 'status', 1 )
                ->where ( 'professional_id', $professional->id )
                ->first ();
        } catch ( ModelNotFoundException $e ) {
            return response ()->json ( [ 
                'message' => trans ( 'api.message33' ),
                'data'    => [],
            ], 404 );
        } catch ( Throwable $e ) {
            return response ()->json ( [ 
                'message' => $e->getMessage (),
                'data'    => [],
            ], 400 );
        }

        $subscription_list = SubscriptionHistory::orderBy ( 'id', 'DESC' )
            ->where ( 'status', 1 )
            ->where ( 'professional_id', $professional->id )
            ->get ();

        if ( !empty ( $subscriptions ) && Carbon::parse ( $subscriptions->validate_till, $subscriptions->timezone )->greaterThan ( Carbon::now ( $subscriptions->timezone ) ) ) {
            $subscription = true;
            $message      = trans ( 'api.message38' );
        } else
            $message = trans ( 'api.message39' );

        return response ()->json ( [ 
            'message' => $message,
            'data'    => [ 
                'active_subscription' => $subscription,
                'subscription_list'   => $subscription_list,
            ],
        ], 200 );
    }

}
