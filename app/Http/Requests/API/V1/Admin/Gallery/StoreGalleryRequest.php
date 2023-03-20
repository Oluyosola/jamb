<?php

namespace App\Http\Requests\API\V1\Admin\Gallery;

use App\Enums\GalleryType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
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
            'type' => [
                'nullable',
                new EnumValue(GalleryType::class),
            ],
            'gallery' => 'required_if:type,' . GalleryType::MEDIA . '|array',
            'gallery.*' => 'required|image',
        ];
    }
}
