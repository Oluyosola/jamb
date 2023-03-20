<?php

namespace App\Http\Requests\API\V1\Admin\BankDetail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBankDetailRequest extends FormRequest
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
            'owner_type' => 'required|string|max:255|in:artisan,admin,user',
            'owner_id' => [
                'required',
                'integer',
                Rule::when($this->owner_type === 'artisan', 'exists:artisans,id'),
                Rule::when($this->owner_type === 'admin', 'exists:admins,id'),
                Rule::when($this->owner_type === 'user', 'exists:users,id'),
            ],
            'account_number' => 'required|string|digits:10',
            'bank_name' => 'required|string|max:255',
            'account_type' => 'required|string|max:255|in:current,savings',
            'account_holder_name' => 'required|string|max:255',
        ];
    }
}
