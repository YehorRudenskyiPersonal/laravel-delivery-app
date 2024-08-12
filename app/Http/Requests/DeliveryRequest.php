<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
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
            'package.width' => 'required|numeric',
            'package.height' => 'required|numeric',
            'package.length' => 'required|numeric',
            'package.weight' => 'required|numeric',
            'recipient.name' => 'required|string',
            'recipient.phone' => 'required|string',
            'recipient.email' => 'required|email',
            'recipient.address' => 'required|string',
            'service' => 'nullable|string', // Added field for the service, if it's not provided value from ENV will be taken
        ];
    }
}
