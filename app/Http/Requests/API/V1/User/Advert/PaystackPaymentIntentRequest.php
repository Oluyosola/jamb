<?php

namespace App\Http\Requests\API\V1\User\Advert;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaystackPaymentIntentRequest extends FormRequest
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
            'callbackUrl' => 'required|url',
            'advert_id' => [
                'required',
                Rule::exists('adverts', 'id')
            ],
        ];
    }
}
