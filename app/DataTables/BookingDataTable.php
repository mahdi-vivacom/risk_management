<?php

namespace App\DataTables;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BookingDataTable extends DataTable
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
            ->addColumn ( 'professional', function ($query) {
                return $query->Professional->fullname ?? '';
            } )
            ->addColumn ( 'booking_id', function ($query) {
                return $query->id;
            } )
            ->addColumn ( 'location', function ($query) {
                return $query->location;
            } )
            ->addColumn ( 'client', function ($query) {
                return $query->Client->fullname ?? '';
            } )
            ->addColumn ( 'area', function ($query) {
                return $query->Area->name ?? '';
            } )
            ->addColumn ( 'skill', function ($query) {
                return $query->Skill->name ?? '';
            } )
            ->addColumn ( 'created_at', function ($query) {
                return $query->created_at->format ( 'Y-m-d H:i' );
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
            ->filterColumn('client', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Client', function ($query) use ($keyword) {
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
            ->filterColumn('skill', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('Skill', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query ( Booking $model ) : QueryBuilder
    {
        return $model->newQuery ();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html () : HtmlBuilder
    {
        return $this->builder ()
            ->setTableId ( 'Booking-DataTable' )
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
            Column::make ( 'booking_id' )->title ( trans ( 'admin_fields.booking_no' ) ),
            Column::make ( 'client' )->title ( trans ( 'admin_fields.client_rating_points' ) ),
            Column::make ( 'professional' )->title ( trans ( 'admin_fields.client_comment' ) ),
            Column::make ( 'skill' )->title ( trans ( 'admin_fields.skill' ) ),
            Column::make ( 'status' )->title ( trans ( 'admin_fields.status' ) ),
            Column::make ( 'created_at' )->title ( trans ( 'admin_fields.date_time' ) ),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename () : string
    {
        return 'Booking_' . date ( 'YmdHis' );
    }
}
