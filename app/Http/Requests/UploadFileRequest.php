<?php

namespace App\Http\Requests;

use App\Rules\HasHeaders;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UploadFileRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $type = $this->input('import_type');

        return Gate::allows('upload-data', $type);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $imports = config('import_data');
        $type = $this->input('import_type');
        $rules = [
            'import_type' => 'required|string',
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:csv,xlsx'],
        ];
         if ($type && isset($imports[$type])) {
            foreach ($imports[$type]['files'] as $fileKey => $fileData) {
                $requiredHeaders = array_map(fn($h) => $h['label'], $fileData['headers_to_db']);
                $rules["files.{$fileKey}"] = [new HasHeaders($requiredHeaders)];
            }
        }
      
        return $rules;
    }
}
