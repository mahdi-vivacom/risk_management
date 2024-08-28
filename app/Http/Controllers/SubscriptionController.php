<?php

namespace App\Http\Controllers;

use App\Models\CountryArea;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionRequest;
use App\DataTables\SubscriptionDataTable;
use App\DataTables\SubscriptionHistoryDataTable;

class SubscriptionController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct ()
    {
        $this->index      = 'Subscription';
        $this->indexRoute = 'subscriptions.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( SubscriptionDataTable $dataTable )
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render ( 'backend.common.index', $data );
    }

    public function history ( SubscriptionHistoryDataTable $dataTable )
    {
        $data = [
            'title' => trans('admin_fields.subscription_history_list'),
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
            'areas' => CountryArea::where ( 'status', 1 )->get (),
        ];
        return view ( 'backend.subscription.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( SubscriptionRequest $request )
    {
        $model = new Subscription();
        $model->fill ( $request->all () );
        $model->save ();
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show ( Subscription $subscription )
    {
        return $subscription;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( Subscription $subscription )
    {
        $data = [
            'title'        => 'Edit ' . $this->index,
            'areas'        => CountryArea::where ( 'status', 1 )->get (),
            'subscription' => $subscription,
        ];
        return view ( 'backend.subscription.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( SubscriptionRequest $request, Subscription $subscription )
    {
        $subscription->update ( $request->all () );
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( Subscription $subscription )
    {
        $subscription->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [ 'success' => true ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_delete_message'));
    }
}
