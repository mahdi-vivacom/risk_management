<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Country;
use App\Services\ClientService;
use App\Services\PhoneNumberValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class ClientLoginController extends Controller
{
    protected $clientService;
    protected $phoneNumberValidationService;

    public function __construct(ClientService $clientService, PhoneNumberValidationService $phoneNumberValidationService)
    {
        $this->clientService = $clientService;
        $this->phoneNumberValidationService = $phoneNumberValidationService;
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => ['required'],
                'password' => ['required'],
            ]);
            $client = Client::where('phone_number', $request->phone_number)->first();

            if (empty($client) || !Hash::check($request->password, $client->password))

                throw ValidationException::withMessages([
                    'phone_number' => [trans('api.message13')],
                ]);

            return response()->json([
                'message' => trans('api.message10'),
                'data' => $client,
                'token' => $client->createToken('client', ['role:client'])->plainTextToken,
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function signup(Request $request)
    {
        try {
            $alpha2 = null;
            if (!$alpha2 && $request->has('country_id')) {
                $country = Country::find($request->input('country_id'));
                if ($country) {
                    $alpha2 = strtoupper($country->iso_code_2);
                }
            }
            $validation = Validator::make(
                $request->all(),
                [
                    // 'phone_number'    => [ 'required', 'regex:/^[0-9+]+$/', Rule::unique ( Client::class)->whereNull ( 'deleted_at' ) ],
                    'phone_number' => ['required', 'phone:' . $alpha2, Rule::unique(Client::class)->whereNull('deleted_at')],
                    'email' => ['email', 'nullable', 'max:255', Rule::unique(Client::class)->whereNull('deleted_at')],
                    'password' => ['required', 'max:255', 'min:6'],
                    'gender' => ['nullable', 'in:1,2,3'],
                    'country_id' => ['required', 'exists:countries,id'],
                    'country_area_id' => ['exists:country_areas,id'],
                    'first_name' => ['required'],
                    'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                ],
            );

            if ($validation->fails())

                return response()->json([
                    'message' => $validation->errors()->all(),
                    'data' => null,
                ], 401);
            else
                if ($request->hasFile('profile_image')) {
                    $request->profile_image = $this->clientService->profile_image_upload($request->file('profile_image'));
                }

            $request->phone_number = $this->phoneNumberValidationService->getInternationalNumber($request->phone_number, $request->country_id);

            $client = Client::create([
                'country_id' => $request->country_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'gender' => $request->gender,
                'profile_image' => $request->profile_image,
                'password' => Hash::make($request->password),
                'referral_code' => $this->clientService->generateUniqueReferralCode(),
                'country_area_id' => $request->country_area_id,
            ]);

            return response()->json([
                'message' => trans('api.message9'),
                'data' => $client,
            ], 201);
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
            $data = $request->user();
            $data['profile_image'] = env('APP_URL') . isset($data['profile_image']) ? $data['profile_image'] : 'static-images/no-image.png';

            return response()->json([
                'message' => trans('api.message11'),
                'data' => $data,
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => trans('api.message12'),
                'data' => null,
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function change_password(Request $request)
    {
        try {
            $request->validate([
                'old_password' => ['required', 'max:255', 'min:6'],
                'new_password' => ['required', 'max:255', 'min:6'],
            ]);

            $user = $request->user();

            if (!Hash::check($request->old_password, $user->password)) {
                throw new \Exception(trans('api.message73'));
            }
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'message' => trans('api.message15'),
                'data' => $user,
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function edit_profile(Request $request)
    {
        try {
            $request->validate([
                'first_name' => ['max:255'],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique(Client::class)
                        ->ignore($this->route('client')->id)
                        ->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        }),
                ],
                'gender' => ['nullable', 'in:1,2,3'],
                'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            $user = $request->user();

            if ($request->hasFile('profile_image')) {
                $user->profile_image = $this->clientService->profile_image_upload($request->file('profile_image'));
            }

            $user->first_name = $request->first_name;
            $user->email = $request->email;
            $user->last_name = $request->last_name;
            $user->gender = $request->gender;
            $user->save();

            return response()->json([
                'message' => trans('api.message16'),
                'data' => $user,
            ], 200);
        } catch (Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }

}
