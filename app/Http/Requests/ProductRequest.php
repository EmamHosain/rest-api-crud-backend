<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'product_quantity' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg'],

        ];
    }


    public function messages(): array
    {
        return [
            'product_name.required' => 'Product name should be required',
            'product_name.string' => 'Product name should be string',
            'product_name.max' => 'Product name maximum 255 character',

            'short_description.required' => 'Description  should be required',
            'short_description.string' => 'Description  must be string',
            'short_description.max' => 'Description  should be 500 characters',

            'price.required' => 'Price  should be required',
            'price.numeric' => 'Price  should be numeric',
            'price.min' => 'Price  should be minimum length 0',

            'product_quantity.required' => 'Quantity should be required',
            'product_quantity.integer' => 'Quantity should be type integer',
            'product_quantity.min' => 'Quantity should be minimum length 0',

            'image.required' => 'Image should be required',
            'image.image' => 'Image should be image',
            'image.mimes' => 'Image should be type png, jpg, and jpeg',

        ];
    }
}
