<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalResource extends JsonResource
{
    public function toArray ( $request )
    {
        $skillNames = $this->Skill->pluck ( 'name' )->implode ( ', ' );
        $skillIds   = $this->Skill->pluck ( 'id' )->implode ( ', ' );
        
        return [ 
            'id'                => $this->id ?? '',
            'full_name'         => $this->full_name ?? '',
            'phone_number'      => $this->phone_number ?? '',
            'skill_category'    => $skillNames ?? '',
            'skill_category_id' => $skillIds ?? '',
            'service_area'      => optional ( $this->Area )->name ?? '',
            'service_area_id'   => optional ( $this->Area )->id ?? '',
            'latitude'          => $this->current_latitude ?? '',
            'longitude'         => $this->current_longitude ?? '',
        ];
    }

}