<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
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
        $id = $this->route('employee.id');
        return [
            'name'          => 'required|max:255|unique:employees,name,'.$id,
            'email'         => 'required|email|max:255|unique:employees,email,'.$id,
            'mobile'        => 'required|numeric|unique:employees,mobile,'.$id,
            'address'       => 'required|max:255',
            'salary'        => 'required|numeric|min:1|max:99999',
            'nid'           => 'nullable|digits:14',
            'joining_date'  => 'required'
        ];
    }

    public function messages(){
        return [];
    }
}
