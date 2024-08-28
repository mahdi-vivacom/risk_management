<?php

namespace App\DataTables;

use App\Models\TopUp;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TopUpDataTable extends DataTable
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
            ->addColumn('amount', function ($query) {
                return $query->amount ?? 0;
            })
            ->addColumn('area', function ($query) {
                return $query->Area->name ?? '';
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('Y-m-d H:i');
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $name = 'TopUp';
                if (auth()->user()->can('top-ups.edit')) {
                    $a = '<a href="' . route('top-ups.edit', ['top_up' => $query->id]) . '" class="btn btn-outline-info btn-sm" title="Edit' . $name . '"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('top-ups.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                    return $a;
                }
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('amount', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('amount', '=', "{$keyword}");
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TopUp $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('TopUp-DataTable')
            ->columns($this->getColumns())
            // ->dom ( 'Bfrtip' )
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
            Column::make('amount')->title(trans('admin_fields.amount')),
            // Column::make ( 'area' )->title ( trans ( 'admin_fields.area' ) ),
            Column::make('status')->title(trans('admin_fields.status')),
            Column::make('created_at')->title(trans('admin_fields.date_time')),
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
        return 'TopUp_' . date('YmdHis');
    }
}
