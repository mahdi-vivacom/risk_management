<?php

namespace App\DataTables;

use App\Models\Refund;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RefundDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('serial_number', function () {
                static $count = 0;
                return ++$count;
            })
            ->addColumn('refund_no', function ($query) {
                return $query->title;
            })
            ->addColumn('professional_id', function ($query) {
                return $query->Professional->full_name ?? '';
            })
            ->addColumn('amount', function ($query) {
                return $query->amount;
            })
            ->addColumn('booking_id', function ($query) {
                return $query->booking_id;
            })
            ->addColumn('charge', function ($query) {
                return $query->charge;
            })
            ->addColumn('details', function ($query) {
                return $query->details;
            })
            ->editColumn('client_id', function ($query) {
                return $query->Client->full_name ?? '';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1
                    ? '<span class="badge bg-success">Approved</span>'
                    : ($query->status == 2
                        ? '<span class="badge bg-warning">Cancelled</span>'
                        : '<span class="badge bg-secondary">Pending</span>');
            })
            ->addColumn('action', function ($query) {
                if (auth()->user()->can('refunds.status')) {
                    $name = 'Refund';
                    $a = '';
                    if ($query->status == 0) {
                        $a .= '<button type="button" class="btn btn-outline-success btn-sm" onclick="confirmRefund(' . $query->id . ', \'' . 1 . '\')" data-toggle="tooltip" data-placement="top" title="Confirm this ' . $name . ' ??"><i class="ri-chat-check-fill"></i></button> ';
                    } elseif ($query->status == 1) {
                        // $a .= '<button type="button" class="btn btn-outline-warning btn-sm" onclick="confirmRefund(' . $query->id . ', \'' . 2 . '\')" data-toggle="tooltip" data-placement="top" title="Cancel this ' . $name . ' ??"><i class="ri-chat-delete-line"></i></button> ';
                    }
                    return $a;
                }
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('professional_id', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Professional', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', "%{$keyword}%")
                            ->orWhere('last_name', 'like', "%{$keyword}%");
                    });
                }
            })
            ->filterColumn('client_id', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Client_id', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', "%{$keyword}%")
                            ->orWhere('last_name', 'like', "%{$keyword}%");
                    });
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Refund $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('refunds-table')
            ->columns($this->getColumns())
            // ->minifiedAjax ()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                // Button::make ( 'add' ),
                Button::make('excel'),
                Button::make('csv'),
                // Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('serial_number')->title(trans('admin_fields.serial_number')),
            Column::make('refund_no')->title(trans('admin_fields.refund_no')),
            Column::make('booking_id')->title(trans('admin_fields.booking_no')),
            Column::make('professional_id')->title(trans('admin_fields.professional')),
            Column::make('client_id')->title(trans('admin_fields.client')),
            Column::make('charge')->title(trans('admin_fields.charge')),
            Column::make('amount')->title(trans('admin_fields.amount')),
            Column::make('details')->title(trans('admin_fields.details')),
            Column::make('status')->title(trans('admin_fields.status')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Refund_' . date('YmdHis');
    }

}
