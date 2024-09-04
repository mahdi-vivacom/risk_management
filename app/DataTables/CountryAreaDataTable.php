<?php

namespace App\DataTables;

use App\Models\CountryArea;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CountryAreaDataTable extends DataTable
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
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->editColumn('timezone', function ($query) {
                return $query->timezone ?? '---';
            })
            ->editColumn('name', function ($query) {
                return $query->name ?? '---';
            })
            ->editColumn('country', function ($query) {
                return $query->Country->name ?? '---';
            })
            ->editColumn('color', function ($query) {
                return !empty($query->color) ? '<span style="display:inline-block; width:20px; height:20px; background-color:' . $query->color . '; border: 1px solid #000;"></span>' : '';
            })
            ->filterColumn('country', function ($query, $keyword) {
                $query->whereHas('Country', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('action', function ($query) {
                $name = 'CountryArea';
                if (auth()->user()->can('country-areas.edit')) {
                    $a = '<a href="' . route('country-areas.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('country-areas.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                    return $a;
                }
            })
            ->rawColumns(['status', 'color', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CountryArea $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('CountryArea-DataTable')
            ->columns($this->getColumns())
            ->dom('Bfrtip')
            ->orderBy(1)
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
            Column::make('name')->title(trans('admin_fields.area')),
            // Column::make('country')->title(trans('admin_fields.country')),
            // Column::make('timezone')->title(trans('admin_fields.timezone')),
            Column::make('color')->title(trans('admin_fields.color')),
            Column::make('status'),
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
        return 'CountryArea_' . date('YmdHis');
    }
}
