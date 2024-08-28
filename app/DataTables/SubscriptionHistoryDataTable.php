<?php

namespace App\DataTables;

use App\Models\SubscriptionHistory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubscriptionHistoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable ( QueryBuilder $query ) : EloquentDataTable
    {
        return ( new EloquentDataTable( $query ) )
            ->addColumn ( 'serial_number', function () {
                static $count = 0;
                return ++$count;
            } )
            ->addColumn ( 'package', function ($query) {
                return $query->Subscription->title ?? '';
            } )
            ->addColumn ( 'professional', function ($query) {
                return $query->Professional->fullname ?? '';
            } )
            ->addColumn ( 'area', function ($query) {
                return $query->Area->name ?? '';
            } )
            ->editColumn ( 'auto_renewal', function ($query) {
                return $query->auto_renewal == 1 ? 'Yes' : 'No';
            } )
            ->addColumn ( 'validate_till', function ($query) {
                return $query->validate_till;
            } )
            ->editColumn ( 'status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            } )
            ->rawColumns ( [ 'status' ] )
            ->setRowId ( 'id' )
            ->filterColumn('professional', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Professional', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', "%{$keyword}%")
                            ->orWhere('last_name', 'like', "%{$keyword}%");
                    });
                }
            })
            ->filterColumn('area', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Area', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
                }
            })
            ->filterColumn('package', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Subscription', function ($query) use ($keyword) {
                        $query->where('title', 'like', "%{$keyword}%");
                    });
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query ( SubscriptionHistory $model ) : QueryBuilder
    {
        return $model->newQuery ();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html () : HtmlBuilder
    {
        return $this->builder ()
            ->setTableId ( 'subscription-history-table' )
            ->columns ( $this->getColumns () )
            // ->minifiedAjax ()
            ->dom ( 'Bfrtip' )
            ->orderBy ( 0 )
            ->selectStyleSingle ()
            ->buttons ( [
                Button::make ( 'excel' ),
                Button::make ( 'csv' ),
                // Button::make ( 'pdf' ),
                Button::make ( 'print' ),
                Button::make ( 'reload' ),
            ] );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns () : array
    {
        return [
            Column::make ( 'serial_number' )->title ( trans ( 'admin_fields.serial_number' ) ),
            Column::make ( 'package' )->title ( trans ( 'admin_fields.subscription_package' ) ),
            Column::make ( 'professional' )->title ( trans ( 'admin_fields.professional' ) ),
            Column::make ( 'area' )->title ( trans ( 'admin_fields.area' ) ),
            Column::make ( 'auto_renewal' )->title ( trans ( 'admin_fields.auto_renewal' ) ),
            Column::make ( 'validate_till' )->title ( trans ( 'admin_fields.validate_till' ) ),
            Column::make ( 'status' )->title ( trans ( 'admin_fields.status' ) ),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename () : string
    {
        return 'Subscription_History_' . date ( 'YmdHis' );
    }
}
