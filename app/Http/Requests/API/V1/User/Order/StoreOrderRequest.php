<?php

namespace App\Http\Requests\API\V1\User\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'artisan_id' => 'required|exists:artisans,id,deleted_at,NULL',
            'is_active' => 'sometimes',
        ];
    }
}
