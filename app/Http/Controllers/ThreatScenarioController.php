<?php

namespace App\Http\Controllers;

use App\DataTables\ThreatScenariosDataTable;
use App\Models\ThreatScenario;
use Illuminate\Http\Request;

class ThreatScenarioController extends Controller
{
    protected $title;
    protected $index;
    protected $indexRoute;

    public function __construct()
    {
        $this->title = 'Threat Scenario';
        $this->index = 'threatScenario';
        $this->indexRoute = 'threat-scenarios';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ThreatScenariosDataTable $dataTable)
    {
        $data = [
            'title' => $this->title . ' List',
        ];
        return $dataTable->render('backend.common.index', $data);
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
            'type' => 'required|in:Threat to movement,Threat to work site,Threat to local population',
            'name' => 'required|string|max:255',
            'definition' => 'required|string',
        ], [
            'name.required' => 'Threat type is required.',
        ]);

        $model = new ThreatScenario();
        $model->fill($validatedData);
        $model->save();

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' ' . trans('admin_fields.data_store_message'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ThreatScenario $threatScenario)
    {
        return $threatScenario;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ThreatScenario $threatScenario)
    {
        $data = [
            'title' => 'Edit ' . $this->title,
            'threatScenario' => $threatScenario,
            'route' => $this->indexRoute,
        ];
        return view('backend.' . $this->index . '.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThreatScenario $threatScenario)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:Threat to movement,Threat to work site,Threat to local population',
            'name' => 'required|string|max:255',
            'definition' => 'required|string',
        ], [
            'name.required' => 'Threat type is required.',
        ]);

        $threatScenario->update($validatedData);

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' ' . trans('admin_fields.data_update_message'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThreatScenario $threatScenario)
    {
        $threatScenario->delete();
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ]);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' ' . trans('admin_fields.data_delete_message'));
    }
}
