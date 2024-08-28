<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Navigation;
use App\DataTables\NavigationDataTable;

class NavigationController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Navigation';
        $this->indexRoute = 'navigations.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( NavigationDataTable $dataTable )
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
        return view ( 'backend.navigation.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [
            'name'     => 'required',
            'slug'     => 'required',
            'image'    => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'sequence' => 'integer',
            'type'     => 'required|in:1,2',
        ] );

        if ( $request->hasFile ( 'image' ) ) {
            $image     = $request->file ( 'image' );
            $extension = $image->getClientOriginalExtension ();
            $imagename = pathinfo ( $image->getClientOriginalName (), PATHINFO_FILENAME );
            $imagename = str_replace ( ' ', '_', $imagename );
            $imagename = substr ( $imagename, 0, 20 );
            $imagename = $imagename . round ( microtime ( true ) * 10 ) . '.' . $extension;
            $imageUrl  = '/backend/navigation/profile/' . $imagename;
            $image->move ( 'backend/navigation/profile/', $imagename );
            $request->image = $imageUrl;
        }

        $model = new Navigation();
        $model->fill ( $validatedData );
        $model->save ();

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_store_message') );
    }

    /**
     * Display the specified resource.
     */
    public function show ( Navigation $navigation )
    {
        return $navigation;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( Navigation $navigation )
    {
        $data = [
            'title'      => 'Edit ' . $this->index,
            'navigation' => $navigation,
        ];
        return view ( 'backend.navigation.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( Request $request, Navigation $navigation )
    {
        $navigation->update ( $request->all () );

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_update_message') );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( Navigation $navigation )
    {
        $navigation->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_delete_message') );
    }
}
