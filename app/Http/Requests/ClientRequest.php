<?php

namespace App\Http\Requests;

use App\Models\Client;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $alpha2 = $this->input('alpha_2') ? strtoupper($this->input('alpha_2')) : null;
        if (!$alpha2 && $this->has('country_id')) {
            $country = Country::find($this->input('country_id'));
            if ($country) {
                $alpha2 = strtoupper($country->iso_code_2);
            }
        }
        return [
            'first_name' => ['required'],
            'last_name' => ['nullable'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'country_area_id' => ['required', 'integer', 'exists:country_areas,id'],
            'phone_number' => ['required', 'phone:' . $alpha2, Rule::unique(Client::class)->whereNull('deleted_at')],
            // 'phone_number'    => [ 'required', 'regex:/^[0-9+]+$/', Rule::unique ( Client::class)->whereNull ( 'deleted_at' ) ],
            'email' => ['nullable', 'email', 'max:255', Rule::unique(Client::class)->whereNull('deleted_at')],
            'password' => ['required', 'max:255', 'min:6'],
            'gender' => ['required', 'in:1,2,3'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['required'],
        ];
    }

}
