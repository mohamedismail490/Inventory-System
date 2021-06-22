<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest{

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|max:255|confirmed',
        ];
    }

    public function messages(){
        return [];
    }

}
