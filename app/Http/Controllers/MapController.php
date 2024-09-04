<?php

namespace App\Http\Controllers;


use App\Models\Configuration;
use App\Http\Controllers\Controller;
use App\Models\CountryArea;
use App\Services\BookingService;

class MapController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }


    public function map()
    {
        $data = [
            'title' => trans('admin_fields.professional_map'),
            'config' => Configuration::first(),
            'regions' => CountryArea::where('status', 1)->where('country_id', 1)->get(),
        ];
        return view('backend.map.map', $data);
    }

    public function heat_map()
    {
        $booking = $this->bookingService->allBookings(false);
        $data = [
            'title' => trans('admin_fields.heat_map'),
            'config' => Configuration::first(),
            'bookings' => $booking->get(['latitude', 'longitude']),
        ];
        return view('backend.map.heatmap', $data);
    }



}
