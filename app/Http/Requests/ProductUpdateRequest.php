<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
        $id = $this->route('product.id');
        return [
            'name'          => 'required|max:255',
            'code'          => 'required|max:255|unique:products,code,'.$id,
            'category_id'   => 'required|integer|exists:categories,id',
            'supplier_id'   => 'required|integer|exists:suppliers,id',
            'root'          => 'nullable|max:255',
            'buying_price'  => 'required|numeric|min:1|max:99999',
            'selling_price' => 'required|numeric|min:1|max:99999',
            'buying_date'   => 'required',
            'quantity'      => 'required|integer|min:1|max:99999',
        ];
    }

    public function messages(){
        return [];
    }
}
