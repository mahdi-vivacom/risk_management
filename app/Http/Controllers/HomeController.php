<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Country;
use App\Models\CountryArea;
use App\Models\Professional;
use App\Models\Skill;
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
            $title = trans('admin_fields.dashboard');
            return view('backend.index', compact('title'));
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
                'title' => trans('admin_fields.dashboard'),
                'clients' => Client::where('status', 1)->count(),
                'professionals' => Professional::where('status', 1)->count(),
                'countries' => Country::where('status', 1)->count(),
                'areas' => CountryArea::where('status', 1)->count(),
                'skills' => Skill::where('status', 1)->count(),
                'booking' => Booking::whereIn('status', [1, 2, 3, 4])->count(),
                'cancelled' => Booking::whereIn('status', [6, 7, 8, 9])->count(),
                'completed' => Booking::where('complete', 1)->count(),
            ];
            return view('backend.common.dashboard', $data);
        } else {
            return view('frontend.auth.login');
        }
    }

}
