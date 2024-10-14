<?php

namespace App\DataTables;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MenusDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('parent_menu', function ($query) {
                if (!isset($query->parent_id)) {
                    return '---';
                }
                return $query->MainMenu->label;
            })
            ->editColumn('label', function ($query) {
                return !empty($query->label) ? $query->label : '---';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $name = 'Menu';
                $a = '';
                if (auth()->user()->can('menus.edit')) {
                    $a = '<a href="' . route('menus.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a> ';
                }
                if (auth()->user()->can('menus.status')) {
                    if ($query->status == 0) {
                        $a .= '<button type="button" class="btn btn-sm btn-outline-success" onclick="confirmStatus(' . $query->id . ', \'' . $name . '\',1)" data-toggle="tooltip" data-placement="top" title="Activate this ' . $name . ' ??"><i class="ri-chat-check-fill"></i></button> ';
                    } else {
                        $a .= '<button type="button" class="btn btn-sm btn-outline-warning" onclick="confirmStatus(' . $query->id . ', \'' . $name . '\',0)" data-toggle="tooltip" data-placement="top" title="Deactivate this ' . $name . ' ??"><i class="ri-chat-delete-fill"></i></button> ';
                    }
                }
                if (auth()->user()->can('menus.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                }
                return $a;
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('parent_menu', function ($query, $keyword) {
                if (isset($keyword)) {
                    $query->whereHas('MainMenu', function ($query) use ($keyword) {
                        $query->where('label', 'like', "%{$keyword}%");
                    });
                }
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Menu $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('Menu-DataTable')
            ->columns($this->getColumns())
            // ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(0, 'ASC')
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
            Column::make('serial')->title(trans('admin_fields.serial_number')),
            Column::make('parent_menu')->title(trans('admin_fields.parent_menu')),
            Column::make('label')->title(trans('admin_fields.menu_label')),
            Column::make('route')->title(trans('admin_fields.route')),
            Column::computed('status')->title(trans('admin_fields.status')),
            Column::computed('action')
                ->title(trans(key: 'admin_fields.action'))
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
        return 'Menus_' . date('YmdHis');
    }
}
