<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateSalaryRequest extends FormRequest
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
        $currentYear = date('Y');
        $pastYear    = $currentYear - 1;
        return [
            'salary_month' => 'required|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'salary_year'  => 'required|in:'.$pastYear.','.$currentYear
        ];
    }

    public function messages()
    {
        return [];
    }
}
