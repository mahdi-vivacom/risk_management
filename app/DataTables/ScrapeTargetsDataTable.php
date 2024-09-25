<?php

namespace App\DataTables;

use App\Models\ScrapeTarget;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ScrapeTargetsDataTable extends DataTable
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
            ->editColumn('area', function ($query) {
                return $query->Area->name ?? '---';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $name = 'ScrapeTarget';
                if (auth()->user()->can('scrapeTargets.edit')) {
                    $a = '<a href="' . route('scrape-targets.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('scrapeTargets.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                    return $a;
                }
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ScrapeTarget $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('ScrapeTarget-DataTable')
            ->columns($this->getColumns())
            ->orderBy(1, 'ASC')
            ->selectStyleSingle()
            ->buttons([
                Button::make('add'),
                Button::make('excel'),
                Button::make('csv'),
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
            Column::make('url')->title(trans('admin_fields.url')),
            Column::make('keywords')->title(trans('admin_fields.keywords')),
            Column::make('area')->title(trans('admin_fields.area')),
            Column::computed('status')->title(trans('admin_fields.status')),
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
        return 'ScrapeTargets_' . date('YmdHis');
    }
}
