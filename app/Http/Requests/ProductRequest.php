<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ProductRequest extends FormRequest
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
        // Determine if the request is for store or update
        return $this->isMethod('post') ? $this->storeRules() : $this->updateRules();
    }

    protected function storeRules()
    {
        return [
            'name' => 'required|string|max:255',
            'shop_brand_id' => 'nullable|exists:shop_brands,id',
            'slug' => 'unique:shop_products,slug|nullable',
            'sku' => 'unique:shop_products,sku|nullable',
            'barcode' => 'unique:shop_products,barcode|nullable',
            'description' => 'nullable|string',
            'qty' => 'required|integer|min:0',
            'security_stock' => 'required|integer|min:0',
            'featured' => 'boolean',
            'is_visible' => 'boolean',
            'requires_shipping' => 'boolean',
            'has_variations' => 'boolean',
            'old_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
        ];
    }

    // Validation rules for update
    protected function updateRules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'shop_brand_id' => 'sometimes|nullable|exists:shop_brands,id',
            'slug' => 'sometimes|required|unique:shop_products,slug,' . $this->route('product'),
            'sku' => 'sometimes|required|unique:shop_products,sku,' . $this->route('product'),
            'barcode' => 'sometimes|required|unique:shop_products,barcode,' . $this->route('product'),
            'description' => 'sometimes|nullable|string',
            'qty' => 'sometimes|required|integer|min:0',
            'security_stock' => 'sometimes|required|integer|min:0',
            'featured' => 'sometimes|boolean',
            'is_visible' => 'sometimes|boolean',
            'requires_shipping' => 'sometimes|boolean',
            'has_variations' => 'sometimes|boolean',
            'old_price' => 'sometimes|nullable|numeric|min:0',
            'price' => 'sometimes|nullable|numeric|min:0',
            'cost' => 'sometimes|nullable|numeric|min:0',
            'published_at' => 'sometimes|nullable|date',
            'seo_title' => 'sometimes|nullable|string|max:60',
            'seo_description' => 'sometimes|nullable|string|max:160',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
