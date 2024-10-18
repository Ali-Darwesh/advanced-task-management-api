<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date|after_or_equal:today ',
            'type' => 'required|in:bug,feature,improvement',
            'status' => 'required|in:open,inProgress,completed,blocked',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'required|integer|exists:users,id',
        ];
    }
}
