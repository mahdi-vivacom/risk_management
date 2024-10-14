<?php

namespace App\Http\Controllers;

use App\DataTables\ScrapeTargetsDataTable;
use App\Models\CountryArea;
use App\Models\ScrapeTarget;
use Illuminate\Http\Request;

class ScrapeTargetController extends Controller
{
    protected $index;
    protected $indexRoute;

    public function __construct()
    {
        $this->index = 'News Scrape Targets';
        $this->indexRoute = 'scrape-targets';
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.index', ['only' => ['index']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.create', ['only' => ['create']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.store', ['only' => ['store']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.show', ['only' => ['show']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.edit', ['only' => ['edit']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.update', ['only' => ['update']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.destroy', ['only' => ['destroy']]);
        $this->middleware('role_or_permission:systemadmin|' . $this->indexRoute . '.status', ['only' => ['status']]);
    }
    public function index(ScrapeTargetsDataTable $dataTable)
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render('backend.common.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create ' . $this->index,
            'areas' => CountryArea::where('status', 1)
                ->where('country_id', 1)
                ->get(),
        ];
        return view('backend.scrapeTarget.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'keywords' => 'required',
            'area_id' => 'required|integer|exists:country_areas,id',
        ]);
        ScrapeTarget::create([
            'url' => $request->url,
            'keywords' => $request->keywords,
            'area_id' => $request->area_id,
        ]);
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_update_message'));
    }

    public function edit(ScrapeTarget $scrapeTarget)
    {
        $data = [
            'title' => 'Edit ' . $this->index,
            'scrapeTarget' => $scrapeTarget,
            'areas' => CountryArea::where('status', 1)
                ->where('country_id', 1)
                ->get(),
        ];
        return view('backend.scrapeTarget.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'url' => 'required|url',
            'keywords' => 'required',
            'area_id' => 'required|integer|exists:country_areas,id',
        ]);

        $scrapeTarget = ScrapeTarget::find($id);
        $scrapeTarget->url = $request->url;
        $scrapeTarget->keywords = $request->keywords;
        $scrapeTarget->area_id = $request->area_id;

        if ($scrapeTarget->save()) {
            $type = 'success';
            $msg = $this->index . ' ' . trans('admin_fields.data_update_message');
        } else {
            $type = 'warning';
            $msg = $this->index . ' not updated.';
        }
        return redirect()->route($this->indexRoute . '.index')->with($type, $msg);
    }

    public function destroy(ScrapeTarget $scrapeTarget)
    {
        $scrapeTarget->delete();
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ]);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('admin_fields.data_delete_message'));
    }

}
