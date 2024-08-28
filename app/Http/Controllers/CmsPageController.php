<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\Country;
use App\DataTables\CmsPageDataTable;
use Illuminate\Http\Request;

class CmsPageController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Cms Page';
        $this->indexRoute = 'cms-pages.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( CmsPageDataTable $dataTable )
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
        return view ( 'backend.cmsPage.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [
            'country_id'  => 'required|integer|exists:countries,id',
            'slug'        => 'required|in:about_us,privacy_policy,terms_&_conditions',
            'title'       => 'required|max:255',
            'content'     => 'required',
            'application' => 'required|in:1,2',
        ] );

        $model = new CmsPage();
        $model->fill ( $validatedData );
        $model->save ();

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_store_message') );
    }

    /**
     * Display the specified resource.
     */
    public function show ( CmsPage $cmsPage )
    {
        return $cmsPage;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( CmsPage $cmsPage )
    {
        $data = [
            'title'     => 'Edit ' . $this->index,
            'countries' => Country::where ( 'status', 1 )->get (),
            'cmsPage'   => $cmsPage,
        ];
        return view ( 'backend.cmsPage.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( Request $request, CmsPage $cmsPage )
    {
        $validatedData = $request->validate ( [
            'country_id'  => 'required|integer|exists:countries,id',
            'slug'        => 'required|in:about_us,privacy_policy,terms_&_conditions',
            'title'       => 'required|max:255',
            'content'     => 'required',
            'application' => 'required|in:1,2',
        ] );

        $cmsPage->update ( $validatedData );

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_update_message') );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( CmsPage $cmsPage )
    {
        $cmsPage->delete ();

        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' .  trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_delete_message') );
    }

}
