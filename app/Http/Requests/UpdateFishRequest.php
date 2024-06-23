<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFishRequest extends FormRequest
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
        $method = $this->method();

        if ($method == 'PUT')
        {
            return [
                'name' => 'required|string',
                'price' => 'required|integer|digits_between:1,6',
                'description' => 'required|string|max:4000',
                'stock' => 'required|integer',
            ];

        } else
        {
            return [
                'name' => 'sometimes|required|string',
                'price' => 'sometimes|required|integer|digits_between:1,6',
                'description' => 'sometimes|required|string|max:4000',
                'stock' => 'sometimes|required|integer',
            ];
        }
    }
}
