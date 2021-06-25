<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
        $id = $this->route('customer.id');
        return [
            'name'    => 'required|max:255|unique:customers,name,'.$id,
            'email'   => 'required|email|max:255|unique:customers,email,'.$id,
            'mobile'  => 'required|numeric|unique:customers,mobile,'.$id,
            'address' => 'required|max:255'
        ];
    }

    public function messages(){
        return [];
    }
}
