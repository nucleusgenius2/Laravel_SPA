<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationExceptionResponse;
use App\Rules\ReCaptcha;
use App\Traits\StructuredResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegistrationRequest extends FormRequest
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
            'name' => 'required|string|max:16|regex:/(^[A-Za-z0-9-_]+$)+/',
            'email' => 'required|email|unique:users|max:30',
            'password' => 'required|string|confirmed|min:6|max:30',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        throw new ValidationExceptionResponse($errors);
    }
}
