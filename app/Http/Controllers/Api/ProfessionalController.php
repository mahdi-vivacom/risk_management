<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TopUp;
use App\Models\SubscriptionHistory;
use App\Models\WalletTransaction;
use App\Services\ProfessionalService;
use App\Services\BookingService;
use App\Services\WaafiService;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Throwable;

class ProfessionalController extends Controller
{
    private $bookingService;
    private $professionalService;
    private $waafiService;
    private $firebaseService;

    public function __construct(BookingService $bookingService, ProfessionalService $professionalService, WaafiService $waafiService, FirebaseService $firebaseService)
    {
        $this->bookingService = $bookingService;
        $this->professionalService = $professionalService;
        $this->waafiService = $waafiService;
        $this->firebaseService = $firebaseService;
    }

    public function location(Request $request)
    {
        try {
            $request->validate([
                'latitude' => ['required'],
                'longitude' => ['required'],
                'bearing' => ['required'],
                'accuracy' => ['required'],
            ]);

            $professional = $request->user();

            $professional->current_latitude = $request->latitude;
            $professional->current_longitude = $request->longitude;
            $professional->bearing = $request->bearing;
            $professional->accuracy = $request->accuracy;
            $professional->last_location_update_time = Carbon::now();

            $professional->save();

            $bookings = Booking::where('professional_id', $professional->id)
                ->whereIn('status', [1])
                ->latest()
                ->first();

            if ($professional->free_busy == 1 && !empty($bookings))

                $log_data = array(
                    'request_type' => trans('api.message52'),
                    'data' => array(
                        'booking_id' => $bookings->id,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                    ),
                    'additional_notes' => trans('api.message52'),
                );

            $this->bookingService->booking_log($log_data);

            $this->firebaseService->OnlineProfessionals($professional);

            $this->firebaseService->Booking($bookings, $bookings->Client, $bookings->Professional);

            return response()->json([
                'message' => trans('api.message52'),
                'data' => $professional,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function online_offline(Request $request)
    {
        try {
            $request->validate([
                'status' => ['required', Rule::in([1, 2])],
                'latitude' => ['required'],
                'longitude' => ['required'],
            ]);

            $professional = $request->user();

            if (($request->status == 1) && ($professional->rating < 1))
                return response()->json([
                    'message' => trans('api.message26'),
                    'data' => null,
                ], 404);

            $subscription = SubscriptionHistory::where('status', 1)
                ->where('professional_id', $professional->id)
                ->first();

            if (empty($subscription))
                return response()->json([
                    'message' => trans('api.message27'),
                    'data' => null,
                ], 404);

            $professional->online_offline = (int) $request->status;
            $professional->free_busy = (int) $request->status == 1 ? 1 : 0;
            $professional->current_latitude = $request->latitude;
            $professional->current_longitude = $request->longitude;
            $professional->save();

            $message = $professional->online_offline == 1 ? trans('api.message29') : trans('api.message30');
            $this->professionalService->createOnlineOffline($request->status, $professional->id);

            $this->firebaseService->OnlineProfessionals($professional);

            return response()->json([
                'message' => $message,
                'data' => $professional,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function topup(Request $request)
    {
        try {
            $request->validate([
                'top_up_amount' => 'required|numeric|regex:/^\d{1,2}(\.\d{1,4})?$/',
            ]);

            $professional = $request->user();
            $topUp = TopUp::where('status', 1)->first();

            if (!$topUp) {
                throw new \Exception('Top-Up not activated');
            }

            $phoneNumber = str_replace("+", "", $professional->phone_number);
            $response = $this->waafiService->waafi_api($request->top_up_amount, $phoneNumber);

            if ($response->status() === 200) {
                if (!empty($response['responseCode']) && ($response['responseCode'] === '2001')) {

                    $professional->wallet_money = $professional->wallet_money + $request->top_up_amount;

                    WalletTransaction::create([
                        'professional_id' => $professional->id,
                        'transaction_type' => 1,
                        'receipt_number' => trans('api.message28'),
                        'amount' => $request->top_up_amount,
                        'description' => trans('api.message28'),
                        'narration' => 3,
                        'booking_id' => null,
                    ]);

                    if ($professional->rating < 1) {
                        $professional->rating = 1;
                    }
                    $professional->save();

                    $message = trans('api.message49');
                } else {
                    $message = trans('api.message50');
                }
            } else {
                $message = trans('api.message51');
            }

            return response()->json([
                'message' => $message,
                'data' => $response->json(),
            ], $response->status());

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function wallet_transaction(Request $request)
    {
        try {
            $request->validate([
                'filter' => 'required|integer|between:1,3',
            ]);

            $data = [];
            $professional = $request->user();
            $request->filter == 3 ? $filter = array(1, 2) : $filter = array($request->filter);

            $currency = $professional->Country->Country->iso_code_3 ?? 'USD';

            $transaction = WalletTransaction::where('professional_id', $professional->id)
                ->whereIn('transaction_type', $filter)
                ->latest()
                ->paginate(10);

            $newArray = $transaction->toArray();

            if (empty($newArray['data'])) {
                $result = array(
                    'wallet_money' => $currency . ' 0.00',
                    'recent_transactoin' => [],
                );
                return response()->json([
                    'message' => trans('api.message47'),
                    'total_pages' => $newArray['last_page'],
                    'current_page' => $newArray['current_page'],
                    'data' => $result,
                ]);
            }
            $next_page_url = $newArray['next_page_url'];
            $next_page_url = $next_page_url == "" ? "" : $next_page_url;

            foreach ($newArray['data'] as $value) {
                $transaction_type = $value['transaction_type'];
                $narration = $value['narration'];
                switch ($transaction_type) {
                    case "1":
                        $transaction_value = trans('api.message36');
                        $value_color = "2ecc71";
                        $image = env('APP_URL') . 'static-images/dollar1.png';
                        break;
                    case "2":
                        $transaction_value = trans('api.message37');
                        $value_color = "e74c3c";
                        $image = env('APP_URL') . 'static-images/dollar.png';
                        break;
                }

                switch ($narration) {
                    case "1":
                        $narration = trans('api.message44');
                        break;
                    case "2":
                        $narration = trans('api.message45');
                        break;
                    case "3":
                        $narration = trans('api.message46') . $value['booking_id'];
                        break;
                    case "4":
                        $narration = trans('api.message47') . $value['booking_id'];
                        break;
                    case "5":
                        $narration = trans('api.message48') . '(' . trans('api.cashback') . ')';
                        break;
                    default:
                        $narration = $value['description'];
                }
                $data[] = array(
                    'transaction_type' => $transaction_value,
                    'amount' => $currency . " " . round($value['amount'], 2),
                    'date' => $value['created_at'],
                    'description' => $value['description'],
                    'narration' => $narration,
                    'value_color' => $value_color,
                    'icon' => $image,
                );
            }
            $result = array(
                'wallet_money' => $currency . " " . ($professional->wallet_money ?? '0.00'),
                'recent_transactoin' => $data,
            );

            return response()->json([
                'message' => trans('api.message47'),
                'total_pages' => $newArray['last_page'],
                'next_page_url' => $next_page_url,
                'current_page' => $newArray['current_page'],
                'data' => $result,
            ], 200);

        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function earnings_revised(Request $request)
    {
        try {
            $request->validate([
                'bill_period' => 'sometimes|required',
            ]);

            $data = [];

            return response()->json([
                'message' => trans('api.bill_period'),
                'data' => $data,
            ], 400);

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

            $professional = $request->user();
            $professional->free_busy = 1;
            $professional->save();

            $this->firebaseService->OnlineProfessionals($professional);

            $booking = Booking::find($request->booking_id);
            $booking->cancel_reason_id = $request->cancel_reason_id;
            $booking->status = 7;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

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
            $booking->professional_id = $request->user()->id;
            $booking->status = 9;
            $booking->save();

            $this->firebaseService->Booking($booking, $booking->Client, $booking->Professional);

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

}
