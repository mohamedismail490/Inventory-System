<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCreateRequest extends FormRequest
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
            'name'    => 'required|max:255|unique:customers',
            'email'   => 'required|email|max:255|unique:customers',
            'mobile'  => 'required|numeric|unique:customers',
            'address' => 'required|max:255',
        ];
    }

    public function messages(){
        return [];
    }
}
