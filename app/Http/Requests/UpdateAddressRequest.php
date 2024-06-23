<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
     * @return array \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PUT')
        {
            return [
                'house_num' => 'required|string|max:8',
                'street_name' => 'required|string',
                'town' => 'required|string',
                'city' => 'required|string',
                'zip_code' => 'required|max:4',
            ];

        } else
        {
            return [
                'house_num' => 'sometimes|required|string|max:8',
                'street_name' => 'sometimes|required|string',
                'town' => 'sometimes|required|string',
                'city' => 'sometimes|required|string',
                'zip_code' => 'sometimes|required|max:4',
            ];
        }
    }
}
