<?php

namespace App\Http\Controllers;

use App\DataTables\ProfessionalDataTable;
use App\Models\Country;
use App\Models\CountryArea;
use App\Models\Professional;
use App\Models\Skill;
use App\Services\ProfessionalService;
use App\Services\PhoneNumberValidationService;
use App\Http\Requests\ProfessionalRequest;
use App\Http\Requests\ProfessionalUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ProfessionalController extends Controller
{
    protected $index;
    protected $indexRoute;
    protected $professionalService;
    protected $phoneNumberValidationService;

    public function __construct(ProfessionalService $professionalService, PhoneNumberValidationService $phoneNumberValidationService)
    {
        $this->index = 'Professional';
        $this->indexRoute = 'professionals';
        $this->professionalService = $professionalService;
        $this->phoneNumberValidationService = $phoneNumberValidationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ProfessionalDataTable $dataTable)
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
            'route' => $this->indexRoute,
            'countries' => Country::where('status', 1)->get(),
            'areas' => CountryArea::where('status', 1)->get(),
            'skills' => Skill::where('status', 1)->get(),
        ];
        return view('backend.professional.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfessionalRequest $request)
    {
        if ($request->hasFile('profile_image')) {
            $profile_image = $this->professionalService->profile_image_upload($request->file('profile_image'));
        }

        $request->phone_number = $this->phoneNumberValidationService->getInternationalNumber($request->phone_number, $request->country_id);

        $filteredData = $request->only(['country_id', 'country_area_id', 'first_name', 'last_name', 'phone_number', 'email', 'gender', 'profile_image']);

        $model = new Professional();
        $model->fill($filteredData);
        $model->profile_image = $profile_image ?? null;
        $model->password = Hash::make($request->password);
        $model->referral_code = $this->professionalService->generateUniqueReferralCode();
        $model->save();

        if (!empty($request->skill)) {
            $model->insertSkill($request->skill);
        }

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Professional $professional)
    {
        return $professional;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professional $professional)
    {
        $data = [
            'title' => 'Edit ' . $this->index,
            'route' => $this->indexRoute,
            'areas' => CountryArea::where('status', 1)->get(),
            'countries' => Country::where('status', 1)->get(),
            'professional' => $professional,
            'skills' => Skill::where('status', 1)->get(),
        ];
        return view('backend.professional.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfessionalUpdateRequest $request, Professional $professional)
    {
        if ($request->has('phone_number')) {
            $phone_number = $this->phoneNumberValidationService->getInternationalNumber($request->phone_number, $request->country_id);
            $professional->update(['phone_number' => $phone_number]);
        }

        if ($request->has('password')) {
            $professional->update(['password' => Hash::make($request->password)]);
        }

        if ($request->hasFile('profile_image')) {
            $imageUrl = $this->professionalService->profile_image_upload($request->file('profile_image'));
            $professional->update(['profile_image' => $imageUrl]);
        }

        $requestData = $request->only(['country_id', 'country_area_id', 'first_name', 'last_name', 'email', 'gender']);

        $professional->update($requestData);

        if (!empty($request->skill)) {
            $professional->insertSkill($request->skill);
        }

        if (!empty($professional['referral_code'])) {
            $professional->update(['referral_code' => $this->professionalService->generateUniqueReferralCode()]);
        }

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professional $professional)
    {
        $professional->delete();

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
        Professional::where('id', $request->id)->update(['status' => $request->status]);

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
