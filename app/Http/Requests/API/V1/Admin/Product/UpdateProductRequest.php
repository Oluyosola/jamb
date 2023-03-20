<?php

namespace App\Http\Requests\API\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'artisan_id' => 'required|integer|exists:artisans,id',
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
            'featured_image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'finish_date' => 'nullable|date|date_format:Y-m-d|after:start_date',

        ];
    }
}
