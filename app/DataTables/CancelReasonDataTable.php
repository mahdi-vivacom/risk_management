<?php

namespace App\DataTables;

use App\Models\CancelReason;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CancelReasonDataTable extends DataTable
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
            ->addColumn('reason', function ($query) {
                return $query->reason ?? '';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->editColumn('reason_for', function ($query) {
                return $query->reason_for == 1 ? 'Client App' : 'Professional App';
            })
            ->addColumn('action', function ($query) {
                $name = 'CancelReason';
                if (auth()->user()->can('cancel-reasons.edit')) {
                    $a = '<a href="' . route('cancel-reasons.edit', ['cancel_reason' => $query->id]) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('cancel-reasons.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                    return $a;
                }
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('reason', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('reason', 'Like', "%{$keyword}%");
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CancelReason $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('CancelReason-DataTable')
            ->columns($this->getColumns())
            // ->minifiedAjax ()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('add'),
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
            Column::make('reason')->title(trans('admin_fields.reason')),
            Column::make('reason_for')->title(trans('admin_fields.app')),
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
        return 'CancelReason_' . date('YmdHis');
    }
}
