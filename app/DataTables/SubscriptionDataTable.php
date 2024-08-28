<?php

namespace App\DataTables;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubscriptionDataTable extends DataTable
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
            ->addColumn('label', function ($query) {
                return $query->label;
            })
            ->addColumn('amount', function ($query) {
                return $query->amount;
            })
            ->addColumn('renewal', function ($query) {
                return $query->renewal;
            })
            ->editColumn('area', function ($query) {
                return $query->Area->name ?? '';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $name = 'Subscription';
                if (auth()->user()->can('subscriptions.edit')) {
                    $a = '<a href="' . route('subscriptions.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('subscriptions.destroy')) {
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
            })
            ->filterColumn('label', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('label', 'like', "%{$keyword}%");
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
    public function query(Subscription $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('Subscription-DataTable')
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
            ])
            ->parameters([
                'initComplete' => 'function() {
                $("#subscriptions-table").on("click", ".delete-btn", function(e) {
                    e.preventDefault();
                    var url = $(this).attr("data-url");
                    if (confirm("Are you sure you want to delete this subscription?")) {
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": $("meta[name=\'csrf-token\']").attr("content")
                            },
                            success: function(response) {
                                // Handle success, maybe show a success message
                                 $(function () {
                                    Toast.fire({
                                        icon: "success",
                                        title: "Subscription deleted successfully"
                                    })
                                });
                                // Reload the DataTable
                                $("#subscriptions-table").DataTable().ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                // Handle error, maybe show an error message
                            }
                        });
                    }
                });
            }',
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('serial_number')->title(trans('admin_fields.serial_number')),
            Column::make('title')->title(trans('admin_fields.title')),
            Column::make('label')->title(trans('admin_fields.label')),
            Column::make('area')->title(trans('admin_fields.area')),
            Column::make('renewal')->title(trans('admin_fields.renewal')),
            Column::make('amount')->title(trans('admin_fields.amount')),
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
        return 'Subscription_' . date('YmdHis');
    }
}
