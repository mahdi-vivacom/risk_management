<?php

namespace App\Services;

use App\Models\Rating;
use App\Services\BookingService;


class FirebaseService
{
    private $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function OnlineProfessionals($request)
    {
        $data = [
            'id' => !empty($request['id']) ? $request['id'] : null,
            'name' => !empty($request['full_name']) ? $request['full_name'] : null,
            'online' => !empty($request['online_offline']) && $request['online_offline'] == 1 ? true : false,
            'active' => !empty($request['free_busy']) && $request['free_busy'] == 1 ? true : false,
            'areaCode' => !empty($request['country_area_id']) ? $request['country_area_id'] : null,
            'skill_id' => !empty($request->Skill->first()->id) ? $request->Skill->first()->id : null,
            'skill_type' => !empty($request->Skill->first()->name) ? $request->Skill->first()->name : null,
            // 'location' => [
            //     'geohash' => !empty($request['geohash']) ? $request['geohash'] : null,
            //     'coordinates' => !empty($request['coordinates']) ? $request['geohash'] : null,
            // ],
        ];

        // $firebase = app ( 'firebase.firestore' )->database ()->collection ( 'OnlineProfessionals' )->newDocument ();

        $firebase = app('firebase.firestore')
            ->database()
            ->collection('Professionals')
            ->document($request['id']);

        $documentSnapshot = $firebase->snapshot();

        if ($documentSnapshot->exists()) {
            $firebase->set($data, ['merge' => true]);
        } else {
            $firebase->set($data);
        }
    }

    public function Booking($booking = [], $client = [], $professional = [])
    {
        $data = [];
        $rating = Rating::where('booking_id', $booking['id'])->first();

        $client_reviewed = !empty($rating) && !empty($rating->client_rating_points) ? true : false;
        $professional_reviewed = !empty($rating) && !empty($rating->professional_rating_points) ? true : false;

        if (!empty($booking)) {
            $status = $this->bookingService->bookingStatus($booking['status']);

            $data = [
                'booking_id' => !empty($booking['id']) ? $booking['id'] : null,
                'start_at' => !empty($booking['created_at']) ? $booking['created_at'] : null,
                'end_at' => !empty($booking['end_at']) ? $booking['end_at'] : null,
                'completed' => !empty($booking['complete']),
                'status_id' => !empty($booking['status']) ? (int) $booking['status'] : 0,
                'status' => !empty($status) ? $status : null,
                'client_reviewed' => $client_reviewed,
                'professional_reviewed' => $professional_reviewed,
            ];
        }

        if (!empty($professional)) {

            $data['professional_info'] = [
                'professional_id' => !empty($professional['id']) ? $professional['id'] : null,
                'name' =>
                    !empty($professional['full_name']) ? $professional['full_name'] : null,
                'phone' =>
                    !empty($professional['phone_number']) ? $professional['phone_number'] : null,
                'profile_img' =>
                    !empty($professional['profile_image']) ? env('APP_URL') . $professional['profile_image'] : null,
                'skill_type' =>
                    !empty($professional->Skill->first()->name) ? $professional->Skill->first()->name : null,
            ];
        } else {

            $data['professional_info'] = [
                'professional_id' => null,
                'name' => null,
                'phone' => null,
                'profile_img' => null,
                'skill_type' => null,
            ];
        }

        if (!empty($client)) {

            $data['client_info'] = [
                'client_id' => !empty($client['id']) ? $client['id'] : null,
                'name' => !empty($client['full_name']) ? $client['full_name'] : null,
                'phone' => !empty($client['phone_number']) ? $client['phone_number'] : null,
                'profile_img' => !empty($client['profile_image']) ? env('APP_URL') . $client['profile_image'] : null,
            ];
        } else {

            $data['client_info'] = [
                'client_id' => null,
                'name' => null,
                'phone' => null,
                'profile_img' => null,
            ];
        }

        $firebase = app('firebase.firestore')
            ->database()
            ->collection('Bookings')
            ->document($booking['id']);

        $documentSnapshot = $firebase->snapshot();

        if ($documentSnapshot->exists()) {
            $firebase->set($data, ['merge' => true]);
        } else {
            $firebase->set($data);
        }
    }

}
