<?php

namespace App\DataTables;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PermissionDataTable extends DataTable
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
            ->editColumn('name', function ($query) {
                return $query->name ?? '';
            })
            ->editColumn('display_name', function ($query) {
                return $query->display_name ?? '';
            })
            ->editColumn('module_name', function ($query) {
                return $query->module_name ?? '';
            })
            ->addColumn('action', function ($query) {
                $name = 'Permission';
                $a = '';
                if (auth()->user()->can('permissions.edit')) {
                    $a = '<a href="' . route('permissions.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a> ';
                }
                if (auth()->user()->can('permissions.destroy')) {
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
    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('Permission-DataTable')
            ->columns($this->getColumns())
            ->dom('Bfrtip')
            ->orderBy(1, 'Asc')
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
            Column::make('name')->title(trans('admin_fields.name')),
            Column::make('display_name')->title(trans('admin_fields.display_name')),
            Column::make('module_name')->title(trans('admin_fields.module_name')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')->title(trans('admin_fields.action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Permission_' . date('YmdHis');
    }
}
