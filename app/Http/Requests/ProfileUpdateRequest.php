<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        // Additional rules for providers
        if ($this->user()->role === 'provider') {
            $rules['bio'] = ['nullable', 'string', 'max:1000'];
            $rules['hourly_rate'] = ['nullable', 'numeric', 'min:100', 'max:10000'];
            $rules['experience_years'] = ['nullable', 'integer', 'min:0', 'max:50'];
            $rules['citizenship_number'] = ['nullable', 'string', 'max:50'];
            $rules['kyc_document'] = ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'];
        }

        return $rules;
    }
}
