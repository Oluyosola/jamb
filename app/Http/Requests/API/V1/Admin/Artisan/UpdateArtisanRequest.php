<?php

namespace App\Http\Requests\API\V1\Admin\Artisan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArtisanRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email:rfc',
            'business_name' => 'nullable|string|max:255',
            'profile' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'state_id' => 'nullable|integer|exists:states,id',
            'city_id' => [
                'sometimes',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->where('state_id', $this->state_id);
                }),
            ],
            'category_id' => 'nullable|integer|exists:categories,id',
            'association_id' => 'required|integer|exists:associations,id',
            'is_active' => 'sometimes|boolean',
            'is_blocked' => 'sometimes|boolean',
            'logo' => 'sometimes|image|max:6020',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'first_name.required' => 'The first name field is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than 255 characters.',
            'last_name.required' => 'The last name field is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'The email must be a valid email address.',
            'business_name.string' => 'The business name must be a string.',
            'business_name.max' => 'The business name may not be greater than 255 characters.',
            'profile.string' => 'The profile must be a string.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone may not be greater than 255 characters.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'city_id.exists' => 'The selected city does not exist for the selected state.',
            'category_id.exists' => 'The selected category must exist.',
            'is_active.boolean' => 'The is active field must be a boolean.',
            'is_blocked.boolean' => 'The is blocked field must be a boolean.',
            'logo.image' => 'The logo must be an image.',
            'logo.max' => 'The logo may not be greater than 6020 kilobytes',
        ];
    }
}
