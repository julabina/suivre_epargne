<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'form.title' => ['required', 'string', 'max:255'],
            'form.description' => ['nullable', 'string', 'max:255'],
            'form.amountGoal' => ['numeric', 'required','min:0'],
            'form.location' => ['string', 'required'],
            'form.customLocation' => ['string', 'nullable', 'max:255'],
            'form.toggleCustomLocation' => ['required', 'boolean'],
            'form.toggleDeadline' => ['required', 'boolean'],
            'form.deadline' => ['string', 'nullable'],
        ];
    }
}
