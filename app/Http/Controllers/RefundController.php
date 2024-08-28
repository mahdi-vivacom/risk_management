<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Booking;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use App\DataTables\RefundDataTable;
use Throwable;

class RefundController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct()
    {
        $this->index = 'Refund';
        $this->indexRoute = 'refunds.index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(RefundDataTable $dataTable)
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render('backend.common.index', $data);
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
    public function show(Refund $refund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refund $refund)
    {
        //
    }

    public function status(Request $request)
    {
        try {
            $refund = Refund::findOrFail($request->id);
            if (!empty($refund)) {
                $refund->status = $request->status;
                $refund->save();
                if ($refund->status == 1) {
                    $booking = Booking::findOrFail($refund->booking_id);
                    $booking->company_cut = null;
                    $booking->save();
                }
                // $data = WalletTransaction::where('booking_id', $booking->id)->first();
                // $data->transaction_type = 0;
                // $data->save();
                return response()->json([
                    'type' => 'success',
                    'message' => trans('admin_fields.refund_approve_message'),
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => trans('admin_fields.refund_not_found_message'),
                ]);
            }
        } catch (Throwable $th) {
            return response()->json([
                'type' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }
}
