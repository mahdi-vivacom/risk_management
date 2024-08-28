<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfessionalResource;
use App\Models\Booking;
use App\Models\Client;
use App\Models\CountryArea;
use App\Models\Professional;
use App\Models\Skill;
use App\Services\BookingService;
use App\Services\ClientService;
use App\Services\FirebaseService;
use App\Services\ProfessionalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class UssdApiController extends Controller
{
    private $professionalService;
    private $firebaseService;
    private $bookingService;
    private $clientService;

    public function __construct(ProfessionalService $professionalService, FirebaseService $firebaseService, BookingService $bookingService, ClientService $clientService)
    {
        $this->professionalService = $professionalService;
        $this->firebaseService = $firebaseService;
        $this->bookingService = $bookingService;
        $this->clientService = $clientService;
    }

    public function skill_category(Request $request)
    {
        try {
            $skills = Skill::where('status', 1)->get();
            $response = [];
            $data = [];

            foreach ($skills as $skill) {
                if (!empty($skill->name)) {
                    $data['id'] = $skill->id;
                    $data['name'] = $skill->name;
                    $response[] = $data;
                }
            }

            $message = !empty($response) ? trans('api.message57') : trans('api.message57');
            $status = !empty($response) ? 200 : 404;

            return response()->json([
                'message' => $message,
                'data' => $response,
            ], $status);

        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function professionals(Request $request)
    {
        try {
            $professionals = $this->professionalService->getProfessionals($request->latitude, $request->longitude, $request->service_area_id, $request->skill_category_id, $request->phone_number);

            $message = $professionals->isNotEmpty() ? trans('api.message40') : trans('api.message41');
            $status = $professionals->isNotEmpty() ? 200 : 404;

            return response()->json([
                'message' => $message,
                'data' => ProfessionalResource::collection($professionals),
            ], $status);

        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function service_area(Request $request)
    {
        try {
            $country_area = CountryArea::where('status', 1)->get();
            $response = [];
            $data = [];

            foreach ($country_area as $c_area) {
                if (!empty($c_area->name)) {
                    $data['id'] = $c_area->id;
                    $data['name'] = $c_area->name;
                    $response[] = $data;
                }
            }

            $message = !empty($response) ? trans('api.message63') : trans('api.message64');
            $status = !empty($response) ? 200 : 404;

            return response()->json([
                'message' => $message,
                'data' => $response,
            ], $status);

        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function booking(Request $request)
    {
        try {
            $request->validate([
                'area_id' => ['required', 'exists:country_areas,id'],
                'skill_category' => ['required', 'exists:skills,id'],
                'professional_id' => ['required', 'exists:professionals,id'],
                'client_phone_number' => ['required'],
            ]);

            $professional = Professional::find($request->professional_id);

            $client = Client::firstOrCreate([
                'phone_number' => $request->client_phone_number
            ], [
                'password' => Hash::make('123456'),
                'referral_code' => $this->clientService->generateUniqueReferralCode(),
            ]);

            $booking = Booking::create([
                'client_id' => $client->id,
                'country_area_id' => $request->area_id,
                'skill_id' => $request->skill_category,
                'status' => 1,
                'professional_id' => $professional->id,
            ]);

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

            return response()->json([
                'message' => trans('api.message77'),
                'booking_id' => $booking->id,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);

        } catch (Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function available(Request $request)
    {
        try {
            $request->validate([
                'professional_id' => ['required', 'exists:professionals,id'],
                'available' => ['required', 'boolean'],
                'booking_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('bookings', 'id')->where(function ($query) {
                        $query->whereIn('status', [1, 3]);
                    }),
                ],
            ]);

            $professional = Professional::find($request->professional_id);
            $professional->free_busy = $request->available == true ? 1 : 0;
            $professional->save();

            if (!empty($request->booking_id)) {
                $booking = Booking::find($request->booking_id);
                $booking->professional_id = $professional->id;
                $booking->status = $request->available == true ? 4 : 2;
                $booking->save();
            }

            $this->firebaseService->OnlineProfessionals($professional);

            $message = $professional->free_busy == 1 ? trans('api.message74') : trans('api.message75');

            return response()->json($message, 200);
        } catch (Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

}
