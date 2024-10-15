<?php

namespace App\DataTables;

use App\Models\ThreatScenario;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ThreatScenariosDataTable extends DataTable
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
            ->editColumn('definition', function ($query) {
                return Str::limit($query->definition, 100);
            })
            ->addColumn('action', function ($query) {
                $name = 'ThreatScenario';
                $a = '';
                if (auth()->user()->can('threat-scenarios.edit')) {
                    $a = '<a href="' . route('threat-scenarios.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a> ';
                }
                if (auth()->user()->can('threat-scenarios.destroy')) {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $name . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $name . ' ??"><i class="ri-delete-bin-2-fill"></i></button> ';
                }
                return $a;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ThreatScenario $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        if (auth()->user()->can('threat-scenarios.create')) {
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
            ->setTableId('ThreatScenario-DataTable')
            ->columns($this->getColumns())
            ->orderBy(0, 'asc')
            ->buttons($buttons);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('serial_number')->title(trans('admin_fields.serial_number')),
            Column::make('type')->title(trans('admin_fields.threat_to')),
            Column::make('name')->title(trans('admin_fields.threat_type')),
            Column::make('definition')->title(trans('admin_fields.definition')),
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
        return 'ThreatScenarios_' . date('YmdHis');
    }
}
