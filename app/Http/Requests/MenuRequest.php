<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize()
    {
        return true;  
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'type' => 'nullable|in:kitchen,bar',
            'stock_item_id' => 'nullable|exists:stock_items,id',
            'stock_quantity' => 'nullable|numeric|min:0.0001',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ];

        return $rules;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => ucwords($this->name),
        ]);
    }
}
