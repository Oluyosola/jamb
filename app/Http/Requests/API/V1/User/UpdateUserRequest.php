<?php

namespace App\Http\Requests\API\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => [
                'nullable',
                'email:rfc,dns',
                Rule::unique('users', 'email')->ignoreModel($this->user()),
            ],
            'area' => 'nullable|string',
            'profile_picture' => 'nullable|image',
            'username' => [
                function ($attribute, $value, $fail) {
                    if ($this->user()->username) {
                        $fail('You can not change your username');
                    }
                },
            ]
        ];
    }
}
