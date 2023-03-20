<?php

namespace App\Http\Requests\API\V1\User\Like;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLikeRequest extends FormRequest
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
            'model' => 'required|string|in:post,comment',
            'model_id' => [
                'required',
                Rule::when($this->model === 'post', 'exists:posts,id'),
                Rule::when($this->model === 'comment', 'exists:comments,id')
            ],
        ];
    }
}
