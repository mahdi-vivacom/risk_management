<?php

namespace App\Http\Requests;

use App\Models\Country;
use App\Models\Professional;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfessionalRequest extends FormRequest
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
        $alpha2 = $this->input('alpha_2') ? strtoupper($this->input('alpha_2')) : null;
        if (!$alpha2 && $this->has('country_id')) {
            $country = Country::find($this->input('country_id'));
            if ($country) {
                $alpha2 = strtoupper($country->iso_code_2);
            }
        }
        return [
            'first_name'      => [ 'required' ],
            'skill'           => [ 'required' ],
            'last_name'       => [ 'nullable' ],
            'country_id'      => [ 'required', 'integer', 'exists:countries,id' ],
            'country_area_id' => [ 'required', 'integer', 'exists:country_areas,id' ],
            'phone_number'    => [ 'required', 'phone:' . $alpha2, Rule::unique(Professional::class)->whereNull('deleted_at')],
            // 'phone_number' => ['required', 'numeric', 'starts_with:+2529', Rule::unique ( Professional::class)->whereNull ( 'deleted_at' ) ],
            'email'           => [ 'email', 'nullable', 'max:255', Rule::unique ( Professional::class)->whereNull ( 'deleted_at' ) ],
            'password'        => [ 'required', 'max:255', 'min:6' ],
            'gender'          => [ 'required', 'in:1,2,3' ],
            'profile_image'   => [ 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048' ],
            // 'status'          => [ 'required' ],
        ];
    }

    public function messages()
    {
        return [
            'phone_number.required' => 'Phone number is required.',
            'phone_number.numeric' => 'Phone number must be a numeric value.',
            'phone_number.starts_with' => 'Phone number format should be +252 9xx xxx xxx.',
        ];
    }

}
