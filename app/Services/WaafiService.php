<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;


class WaafiService
{
    public function waafi_api ( $amount, $phone_number )
    {
        $timestamp = date ( "YmdHisv" );
        $url       = env ( 'WAAFIAPI' );

        $headers   = [ 
            "Content-Type" => "application/json",
        ];

        $data      = [ 
            'schemaVersion' => '1.0',
            'requestId'     => env ( 'REQUESTID' ),
            'timestamp'     => $timestamp,
            'channelName'   => 'WEB',
            'serviceName'   => 'API_PURCHASE',
            'serviceParams' => [ 
                'merchantUid'     => env ( 'MERCHANTUID' ),
                'apiUserId'       => env ( 'APIUSERID' ),
                'apiKey'          => env ( 'APIKEY' ),
                'paymentMethod'   => 'mwallet_account',
                'payerInfo'       => [ 
                    'accountNo'     => $phone_number,
                    'accountType'   => env ( 'ACCOUNTTYPE' ),
                    'accountHolder' => env ( 'ACCOUNTHOLDER' ),
                ],
                'transactionInfo' => [ 
                    'referenceId' => env ( 'REFERENCEID' ),
                    'invoiceId'   => env ( 'INVOICEID' ),
                    'amount'      => (double) $amount,
                    'currency'    => 'USD',
                    'description' => env ( 'ACCOUNTHOLDER' ),
                ],
            ],
        ];

        $response  = Http::withHeaders ( $headers )
            ->withOptions ( [ "verify" => false ] )
            ->post ( $url, $data );

        return $response;
    }

}