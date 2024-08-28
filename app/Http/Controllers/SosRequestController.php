<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SosRequest;
use App\Models\Country;
use App\DataTables\SosRequestDataTable;

class SosRequestController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Sos Request';
        $this->indexRoute = 'sos-requests.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( SosRequestDataTable $dataTable )
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
        ];
        return view ( 'backend.sosRequest.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate ( [
            'country_id'  => 'required|integer|exists:countries,id',
            'number'      => 'required|max:255',
            'application' => 'required|in:1,2',
        ] );

        $model = new SosRequest();
        $model->fill ( $validatedData );
        $model->save ();

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_store_message') );
    }

    /**
     * Display the specified resource.
     */
    public function show(SosRequest $sosRequest)
    {
        return $sosRequest;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SosRequest $sosRequest)
    {
        $data = [
            'title'     => 'Edit ' . $this->index,
            'countries' => Country::where ( 'status', 1 )->get (),
            'sos'       => $sosRequest,
        ];
        return view ( 'backend.sosRequest.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SosRequest $sosRequest)
    {
        $validatedData = $request->validate ( [
            'country_id'  => 'required|integer|exists:countries,id',
            'number'      => 'required|max:255',
            'application' => 'required|in:1,2',
        ] );

        $sosRequest->update ( $validatedData );

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SosRequest $sosRequest)
    {
        $sosRequest->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_delete_message'));
    }
}
