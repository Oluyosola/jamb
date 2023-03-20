<?php

namespace App\Http\Requests\API\V1\Admin\DirectoryCompany;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDirectoryCompanyRequest extends FormRequest
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
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => [
                Rule::exists('sub_categories', 'id')
                    ->where('category_id', $this->category_id)
            ],
            'email' => 'required|email:rfc,dns',
            'website' => 'url',
            'year_founded' => 'integer',
            'description' => 'required|string',
            'is_published' => 'sometimes|boolean',
        ];
    }
}
