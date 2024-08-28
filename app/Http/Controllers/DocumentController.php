<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\DataTables\DocumentDataTable;
use App\Http\Requests\DocumentRequest;

class DocumentController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Document';
        $this->indexRoute = 'documents.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( DocumentDataTable $dataTable )
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
        return view ( 'backend.document.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( DocumentRequest $request )
    {
        $model = new Document();
        $model->fill ( $request->all () );
        $model->save ();
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_store_message') );
    }

    /**
     * Display the specified resource.
     */
    public function show ( Document $document )
    {
        return $document;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( Document $document )
    {
        $data = [
            'title'    => 'Edit ' . $this->index,
            'document' => $document,
        ];
        return view ( 'backend.document.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( DocumentRequest $request, Document $document )
    {
        $document->update ( $request->all () );
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_update_message') );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( Document $document )
    {
        $document->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' .  trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' .  trans('admin_fields.data_delete_message') );
    }
}
