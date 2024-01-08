<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BrandRequest extends FormRequest
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
            'slug' => 'required|string|unique:shop_brands,slug',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'position' => 'nullable|integer',
            'is_visible' => 'required|boolean',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'sort' => 'nullable|integer',
        ];
    }

    // Validation rules for update
    protected function updateRules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|unique:shop_brands,slug,',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'position' => 'nullable|integer',
            'is_visible' => 'sometimes|required|boolean',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'sort' => 'nullable|integer',
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
