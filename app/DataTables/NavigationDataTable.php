<?php

namespace App\DataTables;

use App\Models\Navigation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NavigationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($query) {
                return $query->name ?? '';
            })
            ->addColumn('slug', function ($query) {
                return $query->slug ?? '';
            })
            ->editColumn('sequence', function ($query) {
                return $query->sequence ?? '';
            })
            ->editColumn('type', function ($query) {
                return $query->type == 1 ? 'Client' : 'Professional';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $name = 'Navigation';
                $a = '';
                if (auth()->user()->can('navigations.destroy')) {
                    $a .= '<a href="' . route('navigations.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit ' . $name . '"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('navigations.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                }
                return $a;
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Navigation $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('Navigation-DataTable')
            ->columns($this->getColumns())
            // ->minifiedAjax ()
            ->dom('Bfrtip')
            ->orderBy(0, 'asc')
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
            Column::make('sequence')->title(trans('admin_fields.sequence')),
            Column::make('name')->title(trans('admin_fields.name')),
            Column::make('slug')->title(trans('admin_fields.slug')),
            Column::make('type')->title(trans('admin_fields.type')),
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
        return 'Navigation_' . date('YmdHis');
    }
}
