<?php

namespace App\Http\Controllers;

use App\DataTables\CountryDataTable;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct()
    {
        $this->index = 'Country';
        $this->indexRoute = 'countries';
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.index', ['only' => ['index']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.create', ['only' => ['create']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.store', ['only' => ['store']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.show', ['only' => ['show']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.edit', ['only' => ['edit']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.update', ['only' => ['update']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.destroy', ['only' => ['destroy']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.status', ['only' => ['status']]);
    }
    /**
     * Display a listing of the resource.
     * @param CountryDataTable $dataTable
     * @return mixed
     */
    public function index(CountryDataTable $dataTable)
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
        ];
        return view('backend.country.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'iso_code_2' => 'required',
            'iso_code_3' => 'required',
            'distance_unit' => 'nullable',
            'status' => 'nullable',
            'description' => 'nullable',
            'phone_code' => 'nullable',
        ]);

        $model = new Country();
        $model->fill($validatedData);
        $model->save();

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return $country;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        $data = [
            'title' => 'Edit ' . $this->index,
            'country' => $country,
        ];
        return view('backend.country.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'iso_code_2' => 'required',
            'iso_code_3' => 'required',
            'distance_unit' => 'nullable',
            'status' => 'nullable',
            'description' => 'nullable',
            'phone_code' => 'nullable',
        ]);
        $country->update($validatedData);

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ]);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_delete_message'));
    }

    public function getCountryInfo($id)
    {
        $country = Country::find($id);
        if (isset($country)) {
            return response()->json($country, 200);
        }
        return response()->json(['message' => "Country not found"], 400);
    }
}
