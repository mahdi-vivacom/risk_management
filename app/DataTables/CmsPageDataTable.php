<?php

namespace App\DataTables;

use App\Models\CmsPage;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CmsPageDataTable extends DataTable
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
                return $query->title ?? '';
            })
            ->addColumn('content', function ($query) {
                return $query->content ?? '';
            })
            ->addColumn('country', function ($query) {
                return $query->Country->name ?? '';
            })
            ->editColumn('application', function ($query) {
                return $query->application == 1 ? 'Client App' : 'Professional App';
            })
            ->addColumn('type', function ($query) {
                if ($query->slug == 'about_us')
                    $type = 'About Us';
                elseif ($query->slug == 'privacy_policy')
                    $type = 'Privacy Policy';
                elseif ($query->slug == 'terms_&_conditions')
                    $type = 'Terms & Conditions';
                else
                    $type = Null;
                return $type;
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $name = 'CmsPage';
                $a = '';
                if (auth()->user()->can('cms-pages.edit')) {
                    $a .= '<a href="' . route('cms-pages.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a>';
                }
                if (auth()->user()->can('cms-pages.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                }
                return $a;
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('title', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('title', 'Like', "%{$keyword}%");
                }
            })
            ->filterColumn('content', function ($query, $keyword) {
                $keyword = strtoupper($keyword);
                if (isset($keyword)) {
                    $query->where('content', 'Like', "%{$keyword}%");
                }
            })
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
    public function query(CmsPage $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('CmsPage-DataTable')
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
            Column::make('country')->title(trans('admin_fields.country')),
            Column::make('title')->title(trans('admin_fields.title')),
            // Column::make ( 'content' )->title ( trans ( 'admin_fields.content' ) ),
            Column::make('type')->title(trans('admin_fields.content_type')),
            Column::make('application')->title(trans('admin_fields.app')),
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
        return 'CmsPage_' . date('YmdHis');
    }
}
