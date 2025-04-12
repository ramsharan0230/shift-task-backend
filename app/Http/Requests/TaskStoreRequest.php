<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskStoreRequest extends FormRequest
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
    $rules = [
        "title" => ["string", "max:299"], // Base rule (no "required")
        "description" => ["nullable", "string", "max:2000"],
        "status" => ["string", Rule::in(['TODO', 'INPROGRESS', 'QA', 'DONE'])],
    ];

    // For POST (create), require title and status
    if ($this->isMethod('post')) {
        $rules['title'][] = 'required';
        $rules['status'][] = 'required';
    }

    return $rules;
}

public function messages(): array
{
    return [
        'title.required' => 'Title is required for creating a task',
        'title.max' => 'Title must not exceed 299 characters',
        'description.max' => 'Description must not exceed 2000 characters',
        'status.required' => 'Status is required for creating a task',
        'status.in' => 'Status must be one of: TODO, INPROGRESS, QA, DONE',
    ];
}

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json([
                'data' => $errors,
                'message' => 'Validation failed',
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
