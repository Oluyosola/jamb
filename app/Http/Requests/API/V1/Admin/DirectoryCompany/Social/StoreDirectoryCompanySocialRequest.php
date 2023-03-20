<?php

namespace App\Http\Requests\API\V1\Admin\DirectoryCompany\Social;

use Illuminate\Foundation\Http\FormRequest;

class StoreDirectoryCompanySocialRequest extends FormRequest
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
            'facebook_url' => 'string|nullable',
            'twitter_url' => 'string|nullable',
            'instagram_url' => 'string|nullable',
            'yookos_url' => 'string|nullable',
            'linkedin_url' => 'string|nullable',
            'tiktok_url' => 'string|nullable',
            'skype_url' => 'string|nullable',
            'youtube_url' => 'string|nullable'
        ];
    }
}
