<?php

namespace App\Http\Controllers;

use App\DataTables\CountryAreaDataTable;
use App\Models\Country;
use App\Models\CountryArea;
use App\Models\Configuration;
use App\Models\Skill;
use Illuminate\Http\Request;

class CountryAreaController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Country Area';
        $this->indexRoute = 'country-areas.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( CountryAreaDataTable $dataTable )
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render ( 'backend.common.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create ()
    {
        $data = [
            'title'     => 'Create ' . $this->index,
            'countries' => Country::where ( 'status', 1 )->get (),
            'timezones' => \DateTimeZone::listIdentifiers (),
            'config'    => Configuration::where ( 'status', 1 )->first (),
            'skills'    => Skill::where ( 'status', 1 )->get (),
        ];
        return view ( 'backend.countryArea.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [
            'name'       => 'required',
            'country_id' => 'required|integer|exists:countries,id',
            'lat'        => 'required',
            'timezone'   => 'required|in:' . implode ( ',', \DateTimeZone::listIdentifiers () ),
        ] );

        $model              = new CountryArea();
        $model->name        = $validatedData[ 'name' ];
        $model->country_id  = $validatedData[ 'country_id' ];
        $model->coordinates = $validatedData[ 'lat' ];
        $model->timezone    = $validatedData[ 'timezone' ];
        $model->save ();

        if ( !empty ( $request->skill ) ) {
            $model->insertSkillAreas ( $request->skill );
        }

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_store_message') );
    }

    /**
     * Display the specified resource.
     */
    public function show ( CountryArea $countryArea )
    {
        return $countryArea;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( CountryArea $countryArea )
    {
        $data = [
            'title'       => 'Edit ' . $this->index,
            'countryArea' => $countryArea,
            'countries'   => Country::where ( 'status', 1 )->get (),
            'timezones'   => \DateTimeZone::listIdentifiers (),
            'config'      => Configuration::where ( 'status', 1 )->first (),
            'skills'      => Skill::where ( 'status', 1 )->get (),
        ];
        return view ( 'backend.countryArea.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( Request $request, CountryArea $countryArea )
    {
        $validatedData = $request->validate ( [
            'name'       => 'required',
            'country_id' => 'required|integer|exists:countries,id',
            'lat'        => 'nullable',
            'timezone'   => 'required|in:' . implode ( ',', \DateTimeZone::listIdentifiers () ),
        ] );

        $updateData = [
            'name'       => $validatedData[ 'name' ],
            'country_id' => $validatedData[ 'country_id' ],
            'lat'        => $validatedData[ 'lat' ],
            'timezone'   => $validatedData[ 'timezone' ],
        ];

        if ( !empty ( $validatedData[ 'lat' ] ) ) {
            $updateData[ 'coordinates' ] = $validatedData[ 'lat' ];
        }

        $countryArea->update ( $updateData );

        if ( !empty ( $request->skill ) ) {
            $countryArea->insertSkillAreas ( $request->skill );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_update_message') );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( CountryArea $countryArea )
    {
        $countryArea->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' .  trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_delete_message') );
    }

    public function getCountryArea($id)
    {
        $areas = CountryArea::where('country_id', $id)->get();
        if ($areas->isNotEmpty()) {
            return response()->json($areas, 200);
        } else {
            return response()->json(['message' => 'Country area not found'], 404);
        }
    }
}
