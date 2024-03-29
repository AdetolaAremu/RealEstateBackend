<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|max:150',
            'text' => 'required|max:400',
            'address' => 'required|max:255',
            'price' => 'required|numeric',
            'type' => 'required',
            'city' => 'required',
            'images' => 'required|max:150'
        ];
    }
}
