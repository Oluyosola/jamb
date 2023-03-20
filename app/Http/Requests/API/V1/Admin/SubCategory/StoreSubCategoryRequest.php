<?php

namespace App\Http\Requests\API\V1\Admin\SubCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubCategoryRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                Rule::unique('sub_categories', 'name')
                    ->where('category_id', $this->category->id)
            ],
            'description' => 'required|string',
            'publish' => 'sometimes|boolean',
        ];
    }
}
