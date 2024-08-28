<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize ()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules () : array
    {
        return [ 
            'title'       => [ 'required' ],
            'label'       => [ 'required' ],
            'area_id'     => [ 'required', 'integer', 'exists:country_areas,id' ],
            'amount'      => [ 'required', 'numeric' ],
            'renewal'     => [ 'required' ],
            'details'     => [ 'nullable' ],
            'description' => [ 'nullable' ],
            'status'      => [ 'required' ],
        ];
    }
    
}
