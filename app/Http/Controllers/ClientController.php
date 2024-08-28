<?php

namespace App\Http\Controllers;

use App\DataTables\ClientDataTable;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\Client;
use App\Models\Country;
use App\Models\CountryArea;
use App\Services\ClientService;
use App\Services\PhoneNumberValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $index;
    protected $indexRoute;
    protected $clientService;
    protected $phoneNumberValidationService;

    public function __construct(ClientService $clientService, PhoneNumberValidationService $phoneNumberValidationService)
    {
        $this->index = 'Client';
        $this->indexRoute = 'clients';
        $this->clientService = $clientService;
        $this->phoneNumberValidationService = $phoneNumberValidationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ClientDataTable $dataTable)
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render('backend.common.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Create ' . $this->index,
            'countries' => Country::where('status', 1)->get(),
            'areas' => CountryArea::where('status', 1)->get(),
        ];
        return view('backend.client.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        if ($request->hasFile('profile_image')) {
            $request->profile_image = $this->clientService->profile_image_upload($request->file('profile_image'));
        }

        $model = new Client();

        $request->password = Hash::make($request->password);
        $request->referral_code = $this->clientService->generateUniqueReferralCode();
        $request->phone_number = $this->phoneNumberValidationService->getInternationalNumber($request->phone_number, $request->country_id);

        $filteredData = $request->only(['country_id', 'country_area_id', 'first_name', 'last_name', 'phone_number', 'email', 'gender', 'referral_code', 'profile_image']);

        $model->fill($filteredData);
        $model->save();

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return $client;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $data = [
            'title' => 'Edit ' . $this->index,
            'areas' => CountryArea::where('status', 1)->get(),
            'countries' => Country::where('status', 1)->get(),
            'client' => $client,
        ];
        return view('backend.client.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        if ($request->has('phone_number')) {
            $phone_number = $this->phoneNumberValidationService->getInternationalNumber($request->phone_number, $request->country_id);
            $client->update(['phone_number' => $phone_number]);
        }

        if ($request->has('password')) {
            $client->update(['password' => Hash::make($request->password)]);
        }

        if ($request->hasFile('profile_image')) {
            $imageUrl = $this->clientService->profile_image_upload($request->file('profile_image'));
            $client->update(['profile_image' => $imageUrl]);
        }

        $requestData = $request->only(['country_id', 'country_area_id', 'first_name', 'last_name', 'email', 'gender']);

        $client->update($requestData);

        if (!empty($client['referral_code'])) {
            $client->update(['referral_code' => $this->clientService->generateUniqueReferralCode()]);
        }

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ]);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_delete_message'));
    }

    public function status(Request $request)
    {
        Client::where('id', $request->id)->update(['status' => $request->status]);
        try {
            return response()->json([
                'type' => 'success',
                'message' => $request->status == 1 ? trans('admin_fields.active_status') : trans('admin_fields.inactive_status'),
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }

}
