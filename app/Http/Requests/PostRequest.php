<?php

namespace App\Http\Requests;

use App\Traits\StructuredResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PostRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|min:3|max:255',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'category_id' => 'nullable|int',
            'img' => 'nullable|image|mimes:png,jpg,jpeg',
        ];

        if ($this->isMethod('patch')) {
            $rules['id'] = 'required|int';
        }

        return $rules;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        $this->text = $errors;

        return $this->responseJsonApi();
    }
}
