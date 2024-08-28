<?php

namespace App\Http\Controllers;

use App\DataTables\SosDataTable;
use App\Models\Sos;
use App\Models\Country;
use Illuminate\Http\Request;

class SosController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Sos Number';
        $this->indexRoute = 'sos.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( SosDataTable $dataTable )
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
        return view ( 'backend.sos.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [
            'country_id'  => 'required|integer|exists:countries,id',
            'number'      => 'required|max:255',
            'application' => 'required|in:1,2',
        ] );

        $model = new Sos();
        $model->fill ( $validatedData );
        $model->save ();

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show ( Sos $sos )
    {
        return $sos;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( $id )
    {
        $sos  = Sos::find ( $id );
        $data = [
            'title'     => 'Edit ' . $this->index,
            'countries' => Country::where ( 'status', 1 )->get (),
            'sos'       => $sos,
        ];
        return view ( 'backend.sos.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( Request $request, $id )
    {
        $sos           = Sos::find ( $id );
        $validatedData = $request->validate ( [
            'country_id'  => 'required|integer|exists:countries,id',
            'number'      => 'required|max:255',
            'application' => 'required|in:1,2',
        ] );

        $sos->update ( $validatedData );

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( $id )
    {
        $sos = Sos::find ( $id );
        $sos->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_delete_message') );
    }
}
