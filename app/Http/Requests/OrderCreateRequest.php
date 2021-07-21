<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
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
            'customer_id' => 'required|integer|exists:customers,id',
            'pay'         => 'required|numeric|min:1|max:999999',
            'due'         => 'nullable|numeric|min:1|max:999999',
            'pay_by'      => 'required|in:HandCash,Cheque,GiftCard',

        ];
    }

    public function attributes(){
        return [
            'customer_id' => 'Customer'
        ];
    }

    public function messages(){
        return [];
    }
}
