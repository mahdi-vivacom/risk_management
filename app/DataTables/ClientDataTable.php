<?php

namespace App\DataTables;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClientDataTable extends DataTable
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
            ->addColumn('full_name', function ($query) {
                return $query->full_name ?? '';
            })
            ->addColumn('phone_number', function ($query) {
                return $query->phone_number ?? '';
            })
            ->editColumn('gender', function ($query) {
                return $query->gender == 1 ? 'Male' : ($query->gender == 2 ? 'Female' : 'Others');
            })
            ->addColumn('area', function ($query) {
                return $query->Area->name ?? '';
            })
            ->addColumn('referral_code', function ($query) {
                return $query->referral_code ?? '';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $name = 'Client';
                $a = '';
                if (auth()->user()->can('clients.edit')) {
                    $a .= '<a href="' . route('clients.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit this ' . $name . '"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('clients.status')) {
                    if ($query->status == 0) {
                        $a .= '<button type="button" class="btn btn-sm btn-outline-success ms-1" onclick="confirmStatus(' . $query->id . ', \'' . $name . '\',1)" data-toggle="tooltip" data-placement="top" title="Activate this ' . $name . ' ??"><i class="ri-chat-check-fill"></i></button> ';

                    } else {
                        $a .= '<button type="button" class="btn btn-sm btn-outline-warning ms-1" onclick="confirmStatus(' . $query->id . ', \'' . $name . '\',0)" data-toggle="tooltip" data-placement="top" title="Deactivate this ' . $name . ' ??"><i class="ri-chat-delete-fill"></i></button> ';

                    }
                }
                if (auth()->user()->can('clients.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                }
                return $a;
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('full_name', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%");
                }
            })
            ->filterColumn('phone_number', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('phone_number', 'Like', "%{$keyword}%");
                }
            })
            ->filterColumn('area', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Area', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Client $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('Client-DataTable')
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
            Column::make('full_name')->title(trans('admin_fields.full_name')),
            Column::make('phone_number')->title(trans('admin_fields.phone_number')),
            Column::make('gender')->title(trans('admin_fields.gender')),
            Column::make('area')->title(trans('admin_fields.area')),
            Column::make('referral_code')->title(trans('admin_fields.referral_code')),
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
        return 'Client_' . date('YmdHis');
    }
}
