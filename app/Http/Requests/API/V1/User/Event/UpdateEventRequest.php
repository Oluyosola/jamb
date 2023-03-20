<?php

namespace App\Http\Requests\API\V1\User\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'venue_type' => 'required|in:physical,virtual',
            'venue_details' => 'nullable|string|max:255',
            'country_id' => 'sometimes|exists:countries,id',
            'state_id' => [
                'sometimes',
                Rule::exists('states', 'id')->where(function ($query) {
                    $query->where('country_id', request()->country_id);
                }),
            ],
            'city_id' => [
                'sometimes',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->where('state_id', request()->state_id);
                }),
            ],
            'address' => 'nullable|string|max:255',
            'registration_details' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image',
        ];
    }
}
