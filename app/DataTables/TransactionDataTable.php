<?php

namespace App\DataTables;

use App\Models\Transaction;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
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
            ->addColumn('professional', function ($query) {
                return $query->Professional->fullname ?? '';
            })
            ->addColumn('description', function ($query) {
                return $query->description;
            })
            ->addColumn('receipt_number', function ($query) {
                return $query->receipt_number;
            })
            ->addColumn('amount', function ($query) {
                return $query->amount;
            })
            ->editColumn('narration', function ($query) {
                return $query->narration == 1 ? 'Subscription' : 'Refund';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-warning">Inactive</span>';
            })
            ->rawColumns(['status'])
            ->setRowId('id')
            ->filterColumn('description', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('description', 'Like', "%{$keyword}%");
                }
            })
            ->filterColumn('receipt_number', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('receipt_number', 'Like', "%{$keyword}%");
                }
            })
            ->filterColumn('amount', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('amount', '=', "{$keyword}");
                }
            })
            ->filterColumn('professional', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Professional', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', "%{$keyword}%")
                            ->orWhere('last_name', 'like', "%{$keyword}%");
                    });
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(WalletTransaction $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('transactions-table')
            ->columns($this->getColumns())
            // ->minifiedAjax ()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
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
            Column::make('professional')->title(trans('admin_fields.professional')),
            Column::make('amount')->title(trans('admin_fields.amount')),
            Column::make('description')->title(trans('admin_fields.description')),
            Column::make('receipt_number')->title(trans('admin_fields.receipt_number')),
            Column::make('narration')->title(trans('admin_fields.narration')),
            Column::make('status')->title(trans('admin_fields.status')),
            // Column::computed ( 'action' )
            // ->exportable ( false )
            // ->printable ( false )
            // ->addClass ( 'text-center' ),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
