<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required',
            'stock'=> 'required',
            'category_id'=> 'required',
            'mrp'=> 'required',
            'sp'=> 'required',
            'quantity'=> 'required',
            'packing_quantity'=> 'required',
            'order_limit'=> 'required',
            'status'=> 'required',
        ];
    }
}
