<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ProductVariationRequest extends FormRequest
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
            'shop_product_id' => 'required|exists:shop_products,id',
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'weight_unit' => 'nullable|string|max:255',
            'weight_value' => 'nullable|numeric|min:0',
            'height_unit' => 'nullable|string|max:255',
            'height_value' => 'nullable|numeric|min:0',
            'width_unit' => 'nullable|string|max:255',
            'width_value' => 'nullable|numeric|min:0',
            'depth_unit' => 'nullable|string|max:255',
            'depth_value' => 'nullable|numeric|min:0',
            'volume_unit' => 'nullable|string|max:255',
            'volume_value' => 'nullable|numeric|min:0',
        ];
    }

    // Validation rules for update
    protected function updateRules()
    {
        return [
            'shop_product_id' => 'sometimes|required|exists:shop_products,id',
            'size' => 'sometimes|nullable|string|max:255',
            'color' => 'sometimes|nullable|string|max:255',
            'weight_unit' => 'sometimes|nullable|string|max:255',
            'weight_value' => 'sometimes|nullable|numeric|min:0',
            'height_unit' => 'sometimes|nullable|string|max:255',
            'height_value' => 'sometimes|nullable|numeric|min:0',
            'width_unit' => 'sometimes|nullable|string|max:255',
            'width_value' => 'sometimes|nullable|numeric|min:0',
            'depth_unit' => 'sometimes|nullable|string|max:255',
            'depth_value' => 'sometimes|nullable|numeric|min:0',
            'volume_unit' => 'sometimes|nullable|string|max:255',
            'volume_value' => 'sometimes|nullable|numeric|min:0',
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
