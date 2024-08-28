<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerSupportDataTable;
use App\Models\CustomerSupport;
use Illuminate\Http\Request;

class CustomerSupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index ( CustomerSupportDataTable $dataTable )
    {
        $data = [ 
            'title' => 'Customer Support',
        ];
        return $dataTable->render ( 'backend.common.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerSupport $customerSupport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerSupport $customerSupport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerSupport $customerSupport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerSupport $customerSupport)
    {
        //
    }
}
