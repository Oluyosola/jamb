<?php

namespace App\Http\Requests\API\V1\Admin\DirectoryCompany\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDirectoryCompanyLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'street_address' => 'nullable|string',
            'address_landmark' => 'nullable|string',
            // 'city_id' => 'nullable|exists:cities,id',
            // 'local_government_id' => [
            //     'nullable',
                // Rule::exists('local_governments', 'id')->where('city_id', $this->city_id),
            // ],
            'phone_1' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'website_url' => 'nullable|url',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
