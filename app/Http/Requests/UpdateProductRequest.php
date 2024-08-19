<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'updated_by' => ['required', 'integer', 'exists:users,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
            'alert_stock' => ['required', 'integer', 'min:0'],

        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.integer' => 'User ID must be an integer.',
            'updated_by.integer' => 'Updated by must be an integer.',
            'product_name.required' => 'Product name is required.',
            'product_name.string' => 'Product name must be a string.',
            'product_name.max' => 'Product name should not exceed 255 characters.',
            'brand.required' => 'Brand is required.',
            'brand.string' => 'Brand must be a string.',
            'brand.max' => 'Brand should not exceed 255 characters.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price should not be less than 0.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity should not be less than 0.',
            'image.image' => 'File uploaded should be an image.',
            'image.mimes' => 'Image should be in the format: png, jpg, jpeg.',
            'alert_stock.required' => 'Alert stock is required.',
            'alert_stock.integer' => 'Alert stock must be an integer.',
            'alert_stock.min' => 'Alert stock should not be less than 0.',
        ];
    }
}
