<?php

namespace App\DataTables;

use App\Models\CustomerSupport;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerSupportDataTable extends DataTable
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
            ->addColumn ( 'email', function ($query) {
                return $query->email ?? '';
            } )
            ->addColumn ( 'name', function ($query) {
                return $query->name ?? '';
            } )
            ->addColumn ( 'phone', function ($query) {
                return $query->phone ?? '';
            } )
            ->addColumn ( 'message', function ($query) {
                return $query->message ?? '';
            } )
            ->editColumn ( 'status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            } )
            ->editColumn ( 'application', function ($query) {
                return $query->application == 1 ? 'Client App' : 'Professional App';
            } )
            ->addColumn ( 'created_at', function ($query) {
                return $query->created_at->format ( 'Y-m-d H:i' );
            } )
            ->rawColumns ( [ ] )
            ->setRowId ( 'id' );
    }

    /**
     * Get the query source of dataTable.
     */
    public function query ( CustomerSupport $model ) : QueryBuilder
    {
        return $model->newQuery ();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html () : HtmlBuilder
    {
        return $this->builder ()
            ->setTableId ( 'CustomerSupport-DataTable' )
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
            Column::make ( 'application' )->title ( trans ( 'admin_fields.app' ) ),
            Column::make ( 'email' )->title ( trans ( 'admin_fields.email' ) ),
            Column::make ( 'name' )->title ( trans ( 'admin_fields.name' ) ),
            Column::make ( 'phone' )->title ( trans ( 'admin_fields.phone' ) ),
            Column::make ( 'message' )->title ( trans ( 'admin_fields.query' ) ),
            Column::make ( 'created_at' )->title ( trans ( 'admin_fields.date_time' ) ),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename () : string
    {
        return 'CustomerSupport_' . date ( 'YmdHis' );
    }
}
