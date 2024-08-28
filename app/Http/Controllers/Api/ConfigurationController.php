<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Country;
use App\Models\Configuration;
use App\Models\CustomerSupport;
use App\Models\Language;
use App\Models\Navigation;
use App\Models\TopUp;
use Illuminate\Http\Request;
use Throwable;

class ConfigurationController extends Controller
{
    public function client_config(Request $request)
    {
        try {
            $navigation = Navigation::where('status', 1)->get();

            $country = Country::where('status', 1)->with('CountryArea')->get();

            $language = Language::where('status', 1)->get();

            $configuration = Configuration::where('status', 1)->first();

            $customer_support = CustomerSupport::where('status', 1)->where('application', 1)->first();

            return response()->json([
                'message' => trans('api.message62'),
                'navigation_drawer' => $navigation,
                'countries' => $country,
                'language' => $language,
                'general_config' => $configuration,
                'customer_support' => $customer_support,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function professional_config(Request $request)
    {
        try {
            $country = Country::where('status', 1)->with('CountryArea')->get();

            $language = Language::where('status', 1)->get();

            $configuration = Configuration::where('status', 1)->first();

            $topUp = TopUp::where('status', 1)->first();

            $commission = Commission::where('status', 1)->first();

            $customer_support = CustomerSupport::where('status', 1)->where('application', 2)->first();

            return response()->json([
                'message' => trans('api.message62'),
                'countries' => $country,
                'language' => $language,
                'general_config' => $configuration,
                'customer_support' => $customer_support,
                'top_up_amount' => $topUp->amount ?? 0,
                'company_commission_amount' => $commission->amount ?? 0,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }

}
