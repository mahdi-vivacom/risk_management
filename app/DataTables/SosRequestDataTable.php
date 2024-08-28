<?php

namespace App\DataTables;

use App\Models\SosRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SosRequestDataTable extends DataTable
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
            ->addColumn ( 'booking_id', function ($query) {
                return $query->booking_id ?? '';
            } )
            ->addColumn ( 'number', function ($query) {
                return $query->number ?? '';
            } )
            ->addColumn ( 'country', function ($query) {
                return $query->Country->name ?? '';
            } )
            ->editColumn ( 'application', function ($query) {
                return $query->application == 1 ? 'Client App' : 'Professional App';
            } )
            ->editColumn ( 'status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            } )
            ->addColumn ( 'created_at', function ($query) {
                return $query->created_at->format ( 'Y-m-d H:i' );
            } )
            ->rawColumns ( [ 'status' ] )
            ->setRowId ( 'id' )
            ->filterColumn('country', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Country', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query ( SosRequest $model ) : QueryBuilder
    {
        return $model->newQuery ();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html () : HtmlBuilder
    {
        return $this->builder ()
            ->setTableId ( 'sos-requests-table' )
            ->columns ( $this->getColumns () )
            // ->minifiedAjax ()
            ->dom ( 'Bfrtip' )
            ->orderBy ( 0 )
            ->selectStyleSingle ()
            ->buttons ( [
                // Button::make ( 'add' ),
                Button::make ( 'excel' ),
                Button::make ( 'csv' ),
                // Button::make ( 'pdf' ),
                Button::make ( 'print' ),
                Button::make ( 'reset' ),
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
            Column::make ( 'country' )->title ( trans ( 'admin_fields.country' ) ),
            Column::make ( 'booking_id' )->title ( trans ( 'admin_fields.booking_no' ) ),
            Column::make ( 'number' )->title ( trans ( 'admin_fields.number' ) ),
            Column::make ( 'application' )->title ( trans ( 'admin_fields.app' ) ),
            Column::make ( 'created_at' )->title ( trans ( 'admin_fields.date_time' ) ),
            Column::make ( 'status' )->title ( trans ( 'admin_fields.status' ) ),
            // Column::computed ( 'action' )
            //     ->exportable ( false )
            //     ->printable ( false )
            //     ->addClass ( 'text-center' ),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename () : string
    {
        return 'SosRequest_' . date ( 'YmdHis' );
    }
}
