<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Country;
use App\Models\CountryArea;
use App\Services\PhoneNumberValidationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $phoneNumberValidationService;
    public function __construct(PhoneNumberValidationService $phoneNumberValidationService)
    {
        $this->phoneNumberValidationService = $phoneNumberValidationService;
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        } else {
            return view('frontend.auth.login');
        }
    }

    public function phone_validation(Request $request)
    {
        $request->validate([
            'phone_number' => ['required'],
            'country_id' => ['required'],
        ]);
        return $this->phoneNumberValidationService->check($request->phone_number, $request->country_id);
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $data = [
                // 'title' => trans('admin_fields.dashboard'),
                'areas' => CountryArea::where('status', 1)->count(),
                'config' => Configuration::first(),
                'regions' => CountryArea::where('status', 1)->where('country_id', 1)->get(),
                'countries' => Country::where('status', 1)->count(),
            ];
            return view('backend.common.dashboard', $data);
        } else {
            return view('frontend.auth.login');
        }
    }

}
