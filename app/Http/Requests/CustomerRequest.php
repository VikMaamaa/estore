<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CustomerRequest extends FormRequest
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
        // return [
        //     'name' => 'sometimes|required|string|max:255',
        //     'email' => 'sometimes|required|string|email|unique:shop_customers,email',
        //     'photo' => 'nullable|string',
        //     'gender' => 'sometimes|required|in:male,female',
        //     'phone' => 'nullable|string',
        //     'birthday' => 'nullable|date',
        // ];

        // Determine if the request is for store or update
        return $this->isMethod('post') ? $this->storeRules() : $this->updateRules();
    }

    // Validation rules for store
    protected function storeRules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:shop_customers,email',
            'photo' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string',
            'birthday' => 'nullable|date',
        ];
    }

    // Validation rules for update
    protected function updateRules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|unique:shop_customers,email,',
            'photo' => 'nullable|string',
            'gender' => 'sometimes|required|in:male,female',
            'phone' => 'nullable|string',
            'birthday' => 'nullable|date',
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
