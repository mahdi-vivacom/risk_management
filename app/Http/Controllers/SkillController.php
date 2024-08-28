<?php

namespace App\Http\Controllers;

use App\DataTables\SkillsDataTable;
use App\Models\Skill;
use App\Services\ProfessionalService;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    protected $index;
    protected $indexRoute;
    protected $professionalService;

    public function __construct ( ProfessionalService $professionalService )
    {
        $this->index               = 'Skill';
        $this->indexRoute          = 'skills.index';
        $this->professionalService = $professionalService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index ( SkillsDataTable $dataTable )
    {
        $data = [
            'title' => $this->index . ' List',
        ];
        return $dataTable->render ( 'backend.common.index', $data );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create ()
    {
        $data = [
            'title' => 'Create ' . $this->index,
        ];
        return view ( 'backend.skill.create', $data );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store ( Request $request )
    {
        $validatedData = $request->validate ( [
            'name'        => 'required',
            'description' => 'nullable',
            'status'      => 'nullable',
            'image'       => 'required|mimes:jpeg,png,jpg|dimensions:width=512,height=512',
        ] );

        if ( $request->file ( 'image' ) ) {
            $validatedData[ 'image' ] = $this->professionalService->skill_image_upload ( $request->file ( 'image' ) );
        }

        $model = new Skill();
        $model->fill ( $validatedData );
        // $model->name        = $validatedData[ 'name' ];
        // $model->description = $validatedData[ 'description' ];
        // $model->status      = $validatedData[ 'status' ];
        // $model->image       = $validatedData[ 'image' ];
        $model->save ();

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_store_message') );
    }

    /**
     * Display the specified resource.
     */
    public function show ( Skill $skill )
    {
        return $skill;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit ( Skill $skill )
    {
        $data = [
            'title' => 'Edit ' . $this->index,
            'skill' => $skill,
        ];
        return view ( 'backend.skill.edit', $data );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update ( Request $request, Skill $skill )
    {
        $validatedData = $request->validate ( [
            'name'        => 'required',
            'description' => 'nullable',
            'status'      => 'nullable',
            'image'       => 'required|mimes:jpeg,png,jpg|dimensions:width=512,height=512',
        ] );

        if ( $request->file ( 'image' ) ) {
            $image = $this->professionalService->skill_image_upload ( $request->file ( 'image' ) );
            $skill->update ( [
                'image' => $image,
            ] );
        }

        $skill->update ( [
            'name'        => $validatedData[ 'name' ],
            'description' => $validatedData[ 'description' ],
            'status'      => $validatedData[ 'status' ],
        ] );

        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_update_message') );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy ( Skill $skill )
    {
        $skill->delete ();
        if ( request ()->ajax () ) {
            return response ()->json ( [
                'type'    => 'success',
                'message' => $this->index . ' ' . trans('admin_fields.data_delete_message'),
            ] );
        }
        return redirect ()->route ( $this->indexRoute )->with ( 'success', $this->index . ' ' . trans('admin_fields.data_delete_message') );
    }
}
