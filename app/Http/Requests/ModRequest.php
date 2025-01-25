<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationExceptionResponse;
use App\Traits\StructuredResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ModRequest extends FormRequest
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
            'name' => 'required|string|unique:maps|min:4|max:50|regex:/(^[A-Za-z0-9-_. ]+$)+/',
            'name_dir' => 'required|string|unique:mods|min:4|max:50|regex:/(^[A-Za-z0-9-_. ]+$)+/',
            'description' => 'required|string|max:255',
            'version' => 'required|integer|min:1',
            'type' => 'required|integer|min:0|max:1',
            'url_img' => 'required|image|mimes:png,jpg,jpeg|max:10000',
            'mod_archive' => 'required|file|mimes:zip|max:204800',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        throw new ValidationExceptionResponse($errors);
    }
}
