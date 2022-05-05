<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForecastRequest extends FormRequest
{

    public function rules()
    {
        return [
            'date' => ['required', 'date'],
            'city' => ['sometimes', 'boolean']
        ];
    }
}
