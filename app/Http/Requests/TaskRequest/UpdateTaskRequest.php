<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'due_date' => 'nullable|date|after_or_equal:today ',
            'type' => 'nullable|in:bug,feature,improvement',
            'status' => 'nullable|in:open,inProgress,completed,blocked',
            'priority' => 'nullable|in:low,medium,high',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ];
    }
}
