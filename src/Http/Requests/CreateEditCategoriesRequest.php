<?php

namespace Mixdinternet\Categories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEditCategoriesRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'required',
            'name' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}