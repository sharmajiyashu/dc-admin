<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:categories,title,NULL,id,is_admin,1,deleted_at,NULL',
            'status'=> 'required',
            'image'=> 'required|mimes:png,jpg',
        ];
    }

    function messages()
    {   return [
                'title:required' => 'This Field is required'                
        ];
    }
}
