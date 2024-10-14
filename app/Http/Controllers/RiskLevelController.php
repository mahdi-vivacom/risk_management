<?php

namespace App\Http\Controllers;

use App\DataTables\RiskLevelsDataTable;
use App\Models\RiskLevel;
use Illuminate\Http\Request;

class RiskLevelController extends Controller
{
    protected $title;
    protected $index;
    protected $indexRoute;

    public function __construct()
    {
        $this->title = 'Risk Level';
        $this->index = 'riskLevel';
        $this->indexRoute = 'risk-levels';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(RiskLevelsDataTable $dataTable)
    {
        $data = [
            'title' => $this->title . ' List',
        ];
        return $dataTable->render('backend.' . $this->index . '.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Create ' . $this->title,
            'route' => $this->indexRoute,
        ];
        return view('backend.' . $this->index . '.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'level' => 'required',
            'score_min' => 'required|integer',
            'score_max' => 'required|integer',
            'color' => 'required',
            'action' => 'required',
        ], [
            'score_min.required' => 'The minimum score field is required.',
            'score_max.required' => 'The maximum score field is required.',
        ]);

        $model = new RiskLevel();
        $model->fill($validatedData);
        $model->save();

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' ' . trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RiskLevel $riskLevel)
    {
        return $riskLevel;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RiskLevel $riskLevel)
    {
        $data = [
            'title' => 'Edit ' . $this->title,
            'riskLevel' => $riskLevel,
            'route' => $this->indexRoute,
        ];
        return view('backend.' . $this->index . '.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RiskLevel $riskLevel)
    {
        $validatedData = $request->validate([
            'level' => 'required',
            'score_min' => 'required|integer',
            'score_max' => 'required|integer',
            'color' => 'required',
            // 'action' => 'required',
        ], [
            'score_min.required' => 'The minimum score field is required.',
            'score_max.required' => 'The maximum score field is required.',
        ]);

        $riskLevel->update($validatedData);

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RiskLevel $riskLevel)
    {
        $riskLevel->delete();
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ]);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' ' . trans('admin_fields.data_delete_message'));
    }
}
