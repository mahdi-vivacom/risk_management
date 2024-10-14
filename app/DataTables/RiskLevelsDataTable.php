<?php

namespace App\DataTables;

use App\Models\RiskLevel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RiskLevelsDataTable extends DataTable
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
            ->addColumn('risk_score', function ($query) {
                // return '<td style="background-color: ' . $query->color . '; padding: 10px;">' . $query->score_min . ' - ' . $query->score_max . '</td>';
                return '<div style="background-color:' . $query->color . '; padding:10px; width:100%;">' . $query->score_min . ' - ' . $query->score_max . '</div>';
            })
            ->editColumn('color', function ($query) {
                return !empty($query->color) ? '<span style="display:inline-block; width:20px; height:20px; background-color:' . $query->color . '; border: 1px solid #000;"></span>' : '';
            })
            ->addColumn('actions', function ($query) {
                $name = 'RiskLevel';
                $a = '';
                if (auth()->user()->can('risk-levels.edit')) {
                    $a = '<a href="' . route('risk-levels.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a> ';
                }
                if (auth()->user()->can('risk-levels.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                }
                return $a;
            })
            ->rawColumns(['color', 'risk_score', 'actions'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(RiskLevel $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        if (auth()->user()->can('risk-levels.create')) {
            $buttons[] = Button::make('add');
        }
        $buttons[] = [
            Button::make('excel'),
            Button::make('csv'),
            Button::make('print'),
            Button::make('reset'),
            Button::make('reload'),
        ];
        return $this->builder()
            ->setTableId('RiskLevel-DataTable')
            ->columns($this->getColumns())
            ->orderBy(0, 'desc')
            // ->minifiedAjax()
            // ->dom('Bfrtip')
            ->buttons($buttons);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::make('serial_number')->title(trans('admin_fields.serial_number')),
            Column::make('risk_score')->title(trans('admin_fields.risk_score')),
            Column::make('level')->title(trans('admin_fields.risk_level')),
            // Column::make('score_min')->title(trans('admin_fields.score_min')),
            // Column::make('score_max')->title(trans('admin_fields.score_max')),
            // Column::make('color')->title(trans('admin_fields.color')),
            Column::make('action')->title(trans('admin_fields.action_required')),
            Column::computed('actions')
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
        return 'RiskLevels_' . date('YmdHis');
    }
}
