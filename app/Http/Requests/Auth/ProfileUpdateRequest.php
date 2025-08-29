<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-.\']+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            
            'phone' => ['required', 'string', 'numeric', 'regex:/^[0-9]{10,15}$/'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            
            'github_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'linkedin_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'professional_url' => ['nullable', 'url', 'max:255'],
            
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}