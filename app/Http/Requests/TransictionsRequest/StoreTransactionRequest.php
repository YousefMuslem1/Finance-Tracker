<?php

namespace App\Http\Requests\TransictionsRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'type' => 'required|in:income,expense', 
            'category_id' => 'nullable|exists:categories,id', 
            'amount' => 'required|numeric|min:0.01', 
            'description' => 'nullable|string|max:255', 
        ];
    }
}
