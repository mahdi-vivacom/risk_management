<?php


namespace App\Services;

use App\Models\Booking;
use App\Models\Professional;
use App\Models\Rating;
use Illuminate\Support\Facades\Log;


class BookingService
{
    public function booking_log($data)
    {
        $log_data = array(
            'request_type' => $data['request_type'],
            'request_data' => $data['data'],
            'additional_notes' => $data['additional_notes'],
            'time' => date('Y-m-d H:i:s'),
        );
        Log::channel('booking_daily')->info('Booking data:', $log_data);
    }

    public function create($request)
    {
        return Booking::create([
            'client_id' => $request->client_id,
            'country_area_id' => $request->area,
            'skill_id' => $request->skill_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location' => $request->locaion,
        ]);
    }

    public function bookingStatus($booking_status)
    {
        $booking_text = trans('api.message17'); //Pending

        switch ($booking_status) {
            case "1":
                $booking_text = trans('api.message18'); //Confirmed
                break;
            case "2":
                $booking_text = trans('api.message54'); //Accepted
                break;
            case "3":
                $booking_text = trans('api.message21'); //Start
                break;
            case "4":
                $booking_text = trans('api.message70'); //End
                break;
            case "5":
                $booking_text = trans('api.message22'); //Completed
                break;
            case "6":
                $booking_text = trans('api.message23'); //Cancelled By Client
                break;
            case "7":
                $booking_text = trans('api.message24'); //Cancelled By Professional
                break;
            case "8":
                $booking_text = trans('api.message25'); //Auto Cancelled By Client
                break;
            case "9":
                $booking_text = trans('api.message19'); //Auto Cancelled By Professional
                break;
        }
        return $booking_text;
    }

    public function allBookings($pagination = true)
    {
        $query = Booking::latest();
        $result = $pagination == true ? $query->paginate(25) : $query;
        return $result;
    }

    public function rating($request, $type)
    {
        $data = [
            'booking_id' => $request->booking_id,
        ];

        if ($type == 2) {
            $data['professional_rating_points'] = $request->rating;
            $data['professional_comment'] = $request->comment;
        } else {
            $data['client_rating_points'] = $request->rating;
            $data['client_comment'] = $request->comment;
        }

        $rating = Rating::updateOrCreate([
            'booking_id' => $data['booking_id']
        ], $data);

        return $rating;
    }

    public function findArea($latitude, $longitude, $areas)
    {
        // Loop through each area
        foreach ($areas as $area) {
            // Decode the JSON-encoded coordinates string into an array
            $coordinatesArray = json_decode($area->coordinates, true);

            // Check if decoding was successful
            if (is_array($coordinatesArray)) {
                // Construct the coordinates array for the polygon
                $polygonCoordinates = [];
                foreach ($coordinatesArray as $coordinate) {
                    $polygonCoordinates[] = [
                        "latitude" => $coordinate['latitude'],
                        "longitude" => $coordinate['longitude'],
                    ];
                }

                // Check if the point is within the current polygon
                if ($this->pointInPolygon(["latitude" => $latitude, "longitude" => $longitude], $polygonCoordinates)) {
                    // Return the current area if the point is inside the polygon
                    return $area;
                }
            } else {
                // Handle the case where decoding fails
                throw new \Exception('Invalid coordinates format');
            }
        }

        // Return null if no area contains the point
        return null;
    }

    public function pointInPolygon($p, $polygon)
    {
        $c = 0;
        $p1 = $polygon[0];
        $n = count($polygon);

        for ($i = 1; $i <= $n; $i++) {
            $p2 = $polygon[$i % $n];
            if (
                $p['longitude'] > min($p1['longitude'], $p2['longitude']) &&
                $p['longitude'] <= max($p1['longitude'], $p2['longitude']) &&
                $p['latitude'] <= max($p1['latitude'], $p2['latitude']) &&
                $p1['longitude'] != $p2['longitude']
            ) {
                $xinters = ($p['longitude'] - $p1['longitude']) * ($p2['latitude'] - $p1['latitude']) / ($p2['longitude'] - $p1['longitude']) + $p1['latitude'];
                // Debug: Print intermediate calculations
                // echo "Intersection: " . $xinters . "\n";
                if ($p1['latitude'] == $p2['latitude'] || $p['latitude'] <= $xinters) {
                    $c++;
                }
            }
            $p1 = $p2;
        }

        return $c % 2 != 0;
    }

    public function getProfessional($latitude = '', $longitude = '', $area = null, $skill = null)
    {
        $professional = Professional::with('Skill', 'Area')
            ->select('professionals.*')
            ->leftJoin('bookings', 'professionals.id', 'bookings.professional_id')
            ->whereNotIn('bookings.status', [7, 9]);

        // If latitude and longitude are provided, calculate distance
        if (!empty($latitude) && !empty($longitude)) {
            $professional = $professional->selectRaw(
                '(3958.756 * acos(cos(radians(?)) * cos(radians(professionals.current_latitude)) * cos(radians(professionals.current_longitude) - radians(?)) + sin(radians(?)) * sin(radians(professionals.current_latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
                ->whereNotNull('professionals.current_latitude')
                ->whereNotNull('professionals.current_longitude')
                ->having('distance', '<', 50) // Example: filter professionals within 50 miles radius
                ->orderBy('distance', 'asc');
        }

        // Filter by availability and online status
        $professional = $professional->where('professionals.free_busy', 1) // Available
            ->where('professionals.online_offline', 1); // Online

        // Filter by area if provided
        if (!empty($area)) {
            $country_area_id = (int) $area;
            $professional = $professional->where('professionals.country_area_id', $country_area_id);
        }

        // Filter by skill if provided
        if (!empty($skill)) {
            $skill_category_id = (int) $skill;
            $professional = $professional->whereHas('Skill', function ($query) use ($skill_category_id) {
                $query->where('skills.id', $skill_category_id);
            });
        }

        // Order by bookings.created_at
        $professional = $professional->orderBy('bookings.created_at', 'asc');


        Log::info($professional->toSql(), $professional->getBindings());

        return $professional->first();
    }

}
