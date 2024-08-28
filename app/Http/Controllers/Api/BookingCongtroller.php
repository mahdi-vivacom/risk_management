<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CountryArea;
use App\Models\Professional;
use App\Services\BookingService;
use App\Services\ProfessionalService;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class BookingCongtroller extends Controller
{
    private $bookingService;
    private $professionalService;
    private $firebaseService;

    public function __construct(BookingService $bookingService, ProfessionalService $professionalService, FirebaseService $firebaseService)
    {
        $this->bookingService = $bookingService;
        $this->professionalService = $professionalService;
        $this->firebaseService = $firebaseService;
    }

    public function search(Request $request)
    {
        try {
            $request->validate([
                'latitude' => ['required', 'string'],
                'longitude' => ['required', 'string'],
            ]);

            $areas = CountryArea::with('Skill')->where('status', 1)->get();

            $area = $this->bookingService->findArea($request->latitude, $request->longitude, $areas);

            if (empty($area))
                throw ValidationException::withMessages([
                    'message' => [trans('api.message7')],
                ]);

            $client = $request->user();
            $client->current_latitude = $request->latitude;
            $client->current_longitude = $request->longitude;
            $client->country_area_id = $area->id;
            $client->country_id = $area->country_id;
            $client->save();

            return response()->json([
                'message' => trans('api.message8'),
                'data' => $area,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'skill_id' => ['required', 'integer', 'exists:skills,id'],
            ]);

            $request->client_id = $request->user()->id;
            $request->latitude = $request->user()->current_latitude;
            $request->longitude = $request->user()->current_longitude;
            $request->area = $request->user()->country_area_id;
            $request->skill_id = (int) $request->skill_id;

            $professional = $this->bookingService->getProfessional($request->latitude, $request->longitude, $request->area_id, $request->skill_id);

            if (empty($professional))
                throw ValidationException::withMessages([
                    'message' => [trans('api.message5')],
                ]);

            $data = $this->bookingService->create($request);

            $this->firebaseService->Booking($data, $data->Client);

            $booking = Booking::find($data->id);
            $booking->professional_id = $professional->id;
            $booking->save();

            return response()->json([
                'message' => trans('api.message6'),
                'data' => $data,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function confirm(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    'exists:bookings,id',
                    function ($attribute, $value, $fail) {
                        $booking = Booking::where('id', $value)
                            ->where('status', 0)
                            ->first();
                        if (!$booking) {
                            $fail('The selected booking does not in a active status.');
                        }
                    },
                ],
            ]);

            $booking = Booking::find($request->booking_id);
            $booking->status = 1;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

            return response()->json([
                'message' => trans('api.message3'),
                'data' => $booking,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function active(Request $request)
    {
        try {
            $bookings = Booking::where('client_id', $request->user()->id)->get();

            $records = $bookings->whereIn('status', [0, 1])
                ->where('created_at', '>', Carbon::now()->subMinutes(60));

            foreach ($records as $record) {
                $record->status = 9;
                $record->save();

                $this->firebaseService->Booking($record);
            }

            $bookings = $bookings->where('status', 1);

            if ($bookings->isEmpty())
                return response()->json([
                    'message' => trans('api.message4'),
                    'data' => null,
                ], 404);
            else
                return response()->json([
                    'message' => trans('api.message57'),
                    'data' => $bookings,
                ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function details(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            ]);

            $booking = Booking::with([
                'Skill',
                'Professional' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'email', 'phone_number', 'profile_image', 'rating');
                }
            ])
                ->where('client_id', $request->user()->id)
                ->where('id', $request->booking_id)
                ->first();

            return response()->json([
                'message' => trans('api.message2'),
                'data' => $booking,
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function tracking(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            ]);

            $booking = Booking::find($request->booking_id);

            // $this->firebaseService->Booking ( $booking );

            if (strpos($request->route()->getName(), 'client') !== false)
                $type = 1;
            else
                $type = 2;

            return response()->json([
                'message' => trans('api.message1'),
                'data' => $booking,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function status(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            ]);

            $booking = Booking::find($request->booking_id);

            // $this->firebaseService->Booking ( $booking );

            return response()->json([
                'message' => trans('api.booking_status'),
                'data' => array(
                    'booking_id' => $booking->id,
                    'booking_status' => $this->bookingService->bookingStatus($booking->status),
                ),
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function history(Request $request)
    {
        try {
            $booking = Booking::with([
                'Skill',
                'Professional' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'email', 'phone_number', 'profile_image', 'rating');
                }
            ])
                ->where('client_id', $request->user()->id)
                ->first();

            return response()->json([
                'message' => trans('api.message58'),
                'data' => $booking,
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function auto_cancel(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    'exists:bookings,id',
                    function ($attribute, $value, $fail) {
                        $booking = Booking::where('id', $value)
                            ->whereIn('status', [0, 1])
                            ->first();
                        if (!$booking) {
                            $fail('The selected booking does not in a active status.');
                        }
                    },
                ],
            ]);

            $booking = Booking::find($request->booking_id);
            $booking->status = 8;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client);

            return response()->json([
                'message' => trans('api.message59'),
                'data' => null,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    Rule::exists('bookings', 'id')->where(function ($query) {
                        $query->whereIn('status', [2]);
                    }),
                ],
                'cancel_reason_id' => ['required', 'integer', 'exists:cancel_reasons,id'],
            ]);

            $booking = Booking::find($request->booking_id);
            $booking->cancel_reason_id = $request->cancel_reason_id;
            $booking->status = 6;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

            $professional = Professional::find($booking->professional_id);
            $professional->free_busy = 1;
            $professional->save();

            $this->firebaseService->OnlineProfessionals($professional);

            return response()->json([
                'message' => trans('api.message60'),
                'data' => null,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function accept(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    Rule::exists('bookings', 'id')->where(function ($query) {
                        $query->whereIn('status', [1]);
                    }),
                ],
            ]);

            $professional = $request->user();

            if (!$professional->free_busy = 1) {
                throw new \Exception(trans('api.message75'));
            }

            $professional->free_busy = 0;
            $professional->save();

            $this->firebaseService->OnlineProfessionals($professional);

            $booking = Booking::find($request->booking_id);

            if (!empty($booking)) {
                $booking->status = 2;
                $booking->professional_id = $professional->id;
                $booking->unique_id = uniqid();
                $booking->save();

                $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);
                $this->professionalService->companyCommissionCut($booking->id);
            }

            return response()->json([
                'message' => trans('api.message54'),
                'data' => $booking,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function start(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    Rule::exists('bookings', 'id')->where(function ($query) {
                        $query->whereIn('status', [2]);
                    }),
                ],
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $booking = Booking::find($request->booking_id);
            $booking->status = 3;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

            return response()->json([
                'message' => trans('api.message56'),
                'data' => $booking,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function end(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    Rule::exists('bookings', 'id')->where(function ($query) {
                        $query->whereIn('status', [3]);
                    }),
                ],
            ]);

            $booking = Booking::find($request->booking_id);
            $booking->status = 4;
            $booking->end_at = Carbon::now();
            $booking->complete = 1;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

            $professional = $request->user();
            $professional->free_busy = 1;
            $professional->save();

            $this->firebaseService->OnlineProfessionals($professional);

            return response()->json([
                'message' => trans('api.message70'),
                'data' => $booking,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function history_active(Request $request)
    {
        try {
            $booking = Booking::whereIn('status', [0, 1])
                ->where('professional_id', $request->user()->id)
                ->first();

            return response()->json([
                'message' => trans('api.message57'),
                'data' => $booking,
                'pick_location_visibility' => true,
                'drop_location_visibility' => true,
                'user_description_layout_visibility' => true,
                'user_descriptive_text' => '',
                'status_text' => $this->bookingService->bookingStatus($booking->status),
                'status_text_syle' => 'BOLD',
                'status_text_color' => '333333',
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function history_past(Request $request)
    {
        try {
            $bookings = Booking::with('Skill', 'Professional', 'Client')
                ->where('professional_id', $request->user()->id)
                ->get();

            foreach ($bookings as $booking) {
                $booking->status_text = $this->bookingService->bookingStatus($booking->status);
            }

            return response()->json([
                'message' => trans('api.message58'),
                'data' => $bookings,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function rating(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    Rule::exists('bookings', 'id')->where(function ($query) {
                        $query->where('status', 4);
                    }),
                ],
                'rating' => ['required', Rule::in([1, 2, 3, 4, 5])],
                'comment' => ['string'],
            ]);

            if (strpos($request->route()->getName(), 'client') !== false)
                $type = 1;
            else
                $type = 2;

            $booking = Booking::find($request->booking_id);
            $booking->status = 5;
            $booking->complete = 1;
            $booking->save();

            $data = $this->bookingService->rating($request, $type);

            $this->firebaseService->Booking($booking);

            return response()->json([
                'message' => trans('api.message71'),
                'data' => $data,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function complete(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => [
                    'required',
                    'integer',
                    'exists:bookings,id',
                    function ($attribute, $value, $fail) {
                        $booking = Booking::where('id', $value)
                            ->whereIn('status', [6, 7, 8, 9])
                            ->first();
                        if (!$booking) {
                            $fail('The selected booking does not in a cancel status.');
                        }
                    },
                ],
            ]);

            $booking = Booking::find($request->booking_id);
            $booking->complete = 1;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

            return response()->json([
                'message' => trans('api.message76'),
                'data' => [],
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

}
