<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'employee_id' => 'required|exists:employees,id',
            'name' => 'nullable|string',
            'email' => [
                'nullable',
                 'email',
                 Rule::unique('employees')->ignore($this->employee_id)
                ],
            'phone_number' => 'nullable|string|max:11,min:11',
            'date_hired' => 'nullable|date'
        
        ];
    }
}
