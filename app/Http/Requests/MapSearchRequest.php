<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationExceptionResponse;
use App\Traits\StructuredResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MapSearchRequest extends FormRequest
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
            'name' => 'required|string|unique:maps|min:4|max:50|regex:/(^[a-z0-9-_. ]+$)+/',
            'map_size' => 'required|string|max:20|regex:/(^[a-z0-9-]+$)+/',
            'version' => 'required|integer|min:1',
            'total_player' => 'required|integer|min:1|max:16',
            'rate' => 'required|integer|min:0|max:1',
            'url_img' => 'required|image|mimes:png|max:10000',
            'map_archive' => 'required|file|mimes:zip|max:25600',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        throw new ValidationExceptionResponse($errors);
    }
}
