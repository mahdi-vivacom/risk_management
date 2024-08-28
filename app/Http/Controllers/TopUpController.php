<?php

namespace App\Http\Controllers;

use App\DataTables\TopUpDataTable;
use App\Models\TopUp;
use Illuminate\Http\Request;

class TopUpController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'TopUp';
        $this->indexRoute = 'top-ups';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( TopUpDataTable $dataTable )
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
            'route' => $this->indexRoute,
        ];
        return view ( 'backend.topUp.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [
            'amount' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            // 'area_id' => 'required|integer|exists:country_areas,id',
            'status' => 'required|boolean',
        ], [
            'amount.required' => 'The amount field is required.',
            'amount.regex'    => 'The amount must be a valid number with up to 8 digits and 2 decimal places.',
        ] );

        $model = new TopUp();
        $model->status ( $validatedData[ 'status' ] );

        $model->amount = $validatedData[ 'amount' ];
        // $model->area_id = $validatedData[ 'area_id' ];
        $model->status = $validatedData[ 'status' ];
        $model->save ();


        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_store_message') );
    }

    /**
     * Display the specified resource.
     */
    public function show ( TopUp $topUp )
    {
        return $topUp;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( TopUp $topUp )
    {
        $data = [
            'title' => 'Edit ' . $this->index,
            'topUp' => $topUp,
            'route' => $this->indexRoute,
        ];
        return view ( 'backend.topUp.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( Request $request, TopUp $topUp )
    {
        $validatedData = $request->validate ( [
            'amount' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/',
            // 'area_id' => 'required|integer|exists:country_areas,id',
            'status' => 'required|boolean',
        ], [
            'amount.required' => 'The amount field is required.',
            'amount.regex'    => 'The amount must be a valid number with up to 8 digits and 2 decimal places.',
        ] );

        $topUp->status ( $validatedData[ 'status' ] );

        $topUp->update ( [
            'amount' => $validatedData[ 'amount' ],
            // 'area_id' => $validatedData[ 'area_id' ],
            'status' => $validatedData[ 'status' ],
        ] );

        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( TopUp $topUp )
    {
        $topUp->delete ();

        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute . '.index' )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_delete_message'));
    }
}
