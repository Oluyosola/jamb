<?php

namespace App\Http\Requests\API\V1\User\Advert;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvertRequest extends FormRequest
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
            'advert_plan_id' => 'required|exists:advert_plans,id',
            'advert_url' => 'required|url',
            // 'banner' => [
            //     'required',
            //     Rule::when($this->advert_plan_id == 1, 'dimensions:min_width=350,min_height=350'),
            //     Rule::when($this->advert_plan_id == 2, 'dimensions:min_width=400,min_height=400')
            // ],
        ];
    }
}
