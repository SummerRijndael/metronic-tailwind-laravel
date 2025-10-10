<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:12',
                'regex:/^[a-zA-Z\s]+$/', // letters + spaces only
            ],
            'lastname' => [
                'nullable',
                'string',
                'min:3',
                'max:12',
                'regex:/^[a-zA-Z\s]+$/', // letters + spaces only
            ],
            'mobile' => [
                'nullable',
                'string',
                'regex:/^(0?9\d{9}|639\d{9}|0?9\d{2}-\d{3}-\d{4}|639\d{2}-\d{3}-\d{4})$/'

                // digits only or with dashes "09XXXXXXXXX or 639XXXXXXXXX"
            ],
            'sex' => [
                'nullable',
                'in:Male,Female',
            ],
            'bday' => [
                'nullable',
                'date',
            ],
            'avatar' => [
                'nullable',
                'sometimes',          // only validate if present
                'image',                // checks file type
                'mimes:jpg,jpeg,png',   // only jpg/jpeg/png
                'max:2048',             // max 2MB
                'dimensions:min_width=300,min_height=300,max_width=300,max_height=300', // exact 300x300
            ],
            'avatar_remove' => [
                'nullable', // It's a hidden field, allow it to be absent or null/empty
                'boolean',
            ],
        ];
    }
}
