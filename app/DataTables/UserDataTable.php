<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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
            ->addColumn ( 'name', function ($query) {
                return $query->name ?? '';
            } )
            ->addColumn ( 'phone_number', function ($query) {
                return $query->phone_number ?? '';
            } )
            ->addColumn ( 'role', function ($query) {
                return $query->getRoleDisplayNames () ?? '';
            } )
            ->addColumn ( 'email', function ($query) {
                return $query->email ?? '';
            } )
            ->editColumn ( 'status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            } )
            ->addColumn ( 'action', function ($query) {
                $name = 'User';
                $a    = '';
                if ( auth ()->user ()->can ( 'users.edit' ) ) {
                    $a .= '<a href="' . route ( 'users.edit', $query->id ) . '" class="btn btn-outline-info btn-sm ms-1" title="Edit ' . $name . '?"><i class="ri-edit-box-line"></i></a>';
                }
                if ( auth ()->user ()->can ( 'users.permission.edit' ) ) {
                    $a .= '<a href="' . route ( 'users.permission.edit', $query->id ) . '" class="btn btn-outline-primary btn-sm ms-1" title="Edit ' . $name . ' Permission"><i class="ri-edit-2-fill"></i></a>';
                }
                if ( auth ()->user ()->can ( 'users.status' ) ) {
                    if ( $query->status == 0 ) {
                        $a .= '<button type="button" class="btn btn-sm btn-outline-success ms-1" onclick="confirmStatus(' . $query->id . ', \'' . $name . '\',1)" data-toggle="tooltip" data-placement="top" title="Activate this ' . $name . ' ??"><i class="ri-chat-check-fill"></i></button> ';

                    } else {
                        $a .= '<button type="button" class="btn btn-sm btn-outline-warning ms-1" onclick="confirmStatus(' . $query->id . ', \'' . $name . '\',0)" data-toggle="tooltip" data-placement="top" title="Deactivate this ' . $name . ' ??"><i class="ri-chat-delete-fill"></i></button> ';

                    }
                }
                if ( auth ()->user ()->can ( 'users.destroy' ) ) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm ms-1" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                }
                return $a;
            } )
            ->rawColumns ( [ 'status', 'action' ] )
            ->setRowId ( 'id' )
            ->filterColumn('name', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('name', 'like', "%{$keyword}%");
                }
            })
            ->filterColumn('phone_number', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->where('phone_number', 'like', "%{$keyword}%");
                }
            })
            ->filterColumn('email', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('email', 'like', "%{$keyword}%");
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query ( User $model ) : QueryBuilder
    {
        return $model->newQuery ()->whereHas ( 'roles', function ($query) {
            $query->whereNot ( 'name', 'systemadmin' );
        } );
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html () : HtmlBuilder
    {
        return $this->builder ()
            ->setTableId ( 'User-DataTable' )
            ->columns ( $this->getColumns () )
            ->dom ( 'Bfrtip' )
            ->orderBy ( 0 )
            ->selectStyleSingle ()
            ->buttons ( [
                Button::make ( 'add' ),
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
            Column::make ( 'name' )->title ( trans ( 'admin_fields.name' ) ),
            Column::make ( 'role' )->title ( trans ( 'admin_fields.role' ) ),
            Column::make ( 'phone_number' )->title ( trans ( 'admin_fields.phone_number' ) ),
            Column::make ( 'email' )->title ( trans ( 'admin_fields.email' ) ),
            Column::make ( 'status' )->title ( trans ( 'admin_fields.status' ) ),
            Column::computed ( 'action' )
                ->exportable ( false )
                ->printable ( false )
                ->addClass ( 'text-center' )->title ( trans ( 'admin_fields.action' ) ),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename () : string
    {
        return 'User_' . date ( 'YmdHis' );
    }
}
