<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
        'name' => ['required', 'string', 'max:255'],
        'description' => [
            'nullable',
            'string',
            'max:2000',
        ],

        'due_date' => ['nullable', 'date'],
        'status' => [
            'required',
            Rule::in([
                'not_started',
                'in_progress',
                'in_review',
                'completed',
            ]),
        ],
        'user_id' => ['required', 'integer'],
    ];
    }
}
     

