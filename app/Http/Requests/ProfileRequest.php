<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationExceptionResponse;
use App\Traits\StructuredResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileRequest extends FormRequest
{
    use StructuredResponse;

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
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:30|unique:users,email,' . request()->user()->id,
            'password' => 'required|string|max:30',
            'newPassword' => 'string|max:30'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        throw new ValidationExceptionResponse($errors);
    }
}
