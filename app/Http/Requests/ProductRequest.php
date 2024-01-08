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

    // Validation rules for store
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
            'old_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'type' => 'nullable|in:deliverable,downloadable',
            'backorder' => 'boolean',
            'requires_shipping' => 'boolean',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'weight_value' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string',
            'height_value' => 'nullable|numeric|min:0',
            'height_unit' => 'nullable|string',
            'width_value' => 'nullable|numeric|min:0',
            'width_unit' => 'nullable|string',
            'depth_value' => 'nullable|numeric|min:0',
            'depth_unit' => 'nullable|string',
            'volume_value' => 'nullable|numeric|min:0',
            'volume_unit' => 'nullable|string',
        ];
    }

    // Validation rules for update
    protected function updateRules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'shop_brand_id' => 'sometimes|nullable|exists:shop_brands,id',
            'slug' => 'sometimes|required|unique:shop_products,slug,' ,
            'sku' => 'sometimes|required|unique:shop_products,sku,' ,
            'barcode' => 'sometimes|required|unique:shop_products,barcode,' ,
            'description' => 'sometimes|nullable|string',
            'qty' => 'sometimes|required|integer|min:0',
            'security_stock' => 'sometimes|required|integer|min:0',
            'featured' => 'sometimes|boolean',
            'is_visible' => 'sometimes|boolean',
            'old_price' => 'sometimes|nullable|numeric|min:0',
            'price' => 'sometimes|nullable|numeric|min:0',
            'cost' => 'sometimes|nullable|numeric|min:0',
            'type' => 'sometimes|nullable|in:deliverable,downloadable',
            'backorder' => 'sometimes|boolean',
            'requires_shipping' => 'sometimes|boolean',
            'published_at' => 'sometimes|nullable|date',
            'seo_title' => 'sometimes|nullable|string|max:60',
            'seo_description' => 'sometimes|nullable|string|max:160',
            'weight_value' => 'sometimes|nullable|numeric|min:0',
            'weight_unit' => 'sometimes|nullable|string',
            'height_value' => 'sometimes|nullable|numeric|min:0',
            'height_unit' => 'sometimes|nullable|string',
            'width_value' => 'sometimes|nullable|numeric|min:0',
            'width_unit' => 'sometimes|nullable|string',
            'depth_value' => 'sometimes|nullable|numeric|min:0',
            'depth_unit' => 'sometimes|nullable|string',
            'volume_value' => 'sometimes|nullable|numeric|min:0',
            'volume_unit' => 'sometimes|nullable|string',
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
