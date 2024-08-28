<?php

namespace App\DataTables;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DocumentDataTable extends DataTable
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
            ->addColumn('title', function ($query) {
                return $query->title;
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->editColumn('application', function ($query) {
                return $query->application == 1 ? 'Client App' : 'Professional App';
            })
            ->editColumn('mandatory', function ($query) {
                return $query->mandatory == 1 ? 'Yes' : 'No';
            })
            ->editColumn('validity', function ($query) {
                return $query->validity == 1 ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($query) {
                $name = 'Document';
                if (auth()->user()->can('documents.edit')) {
                    $a = '<a href="' . route('documents.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('documents.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                    return $a;
                }
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('title', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('title', 'like', "%{$keyword}%");
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Document $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('Document-DataTable')
            ->columns($this->getColumns())
            // ->minifiedAjax()
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
            Column::make('title')->title(trans('admin_fields.document_title')),
            Column::make('validity')->title(trans('admin_fields.document_validity')),
            Column::make('mandatory')->title(trans('admin_fields.document_mandatory')),
            Column::make('application')->title(trans('admin_fields.document_for')),
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
        return 'Document_' . date('YmdHis');
    }
}
