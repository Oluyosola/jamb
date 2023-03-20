<?php

namespace App\Http\Requests\API\V1\Admin\Post;

use App\Enums\PostType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                Rule::unique('posts')->ignoreModel($this->post)
            ],
            'body' => 'required|string',
            'meta' => 'required|string',
            'featured_image' => 'nullable|image',
            'categories' => 'required|array|between:1,3',
            'categories.*' => 'required|exists:categories,id',
            'tags' => 'required|array',
            'tags.*' => 'required|string',
            'is_featured' => 'required|boolean',
            'is_published' => 'required|boolean',
            'is_approved' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'post_type' => [
                'required',
                'string',
                new EnumValue(PostType::class)
            ],
        ];
    }
}
