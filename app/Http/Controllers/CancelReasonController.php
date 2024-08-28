<?php

namespace App\Http\Controllers;

use App\DataTables\CancelReasonDataTable;
use App\Models\CancelReason;
use Illuminate\Http\Request;

class CancelReasonController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Cancel Reason';
        $this->indexRoute = 'cancel-reasons.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( CancelReasonDataTable $dataTable )
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
            'title' => 'Create ' . $this->index,
        ];
        return view ( 'backend.cancelReason.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [
            'reason'     => 'required|max:255',
            'reason_for' => 'required|in:1,2',
        ] );

        $model = new CancelReason();
        $model->fill ( $validatedData );
        $model->save ();

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' '. trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show ( CancelReason $cancelReason )
    {
        return $cancelReason;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( CancelReason $cancelReason )
    {
        $data = [
            'title'        => 'Edit ' . $this->index,
            'cancelReason' => $cancelReason,
        ];
        return view ( 'backend.cancelReason.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( Request $request, CancelReason $cancelReason )
    {
        $validatedData = $request->validate ( [
            'reason'     => 'required|max:255',
            'reason_for' => 'required|in:1,2',
        ] );

        $cancelReason->update ( $validatedData );

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' '. trans('admin_fields.data_update_message') );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( CancelReason $cancelReason )
    {
        $cancelReason->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' '. trans('admin_fields.data_delete_message') );
    }
}
