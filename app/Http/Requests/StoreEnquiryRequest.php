<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    /**
     * The contact form is public by design — anyone visiting the site may
     * submit it. Abuse is handled by the throttle middleware on the route and
     * the honeypot below, not by authorization.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:254'],
            // Permissive enough for UAE and international formats: digits,
            // spaces, +, -, and parentheses.
            'phone' => ['required', 'string', 'max:32', 'regex:/^[+(\d][\d\s\-()]{6,31}$/'],
            'project_type' => ['required', 'string', Rule::in(config('site.inquiry.property_types'))],
            'location' => ['nullable', 'string', 'max:200'],
            'project_brief' => ['nullable', 'string', 'max:4000'],

            // Honeypot. Hidden from users and assistive tech, so anything here
            // came from a bot filling every field it found.
            'company' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter a phone number.',
            'phone.regex' => 'Please enter a valid phone number.',
            'project_type.required' => 'Please choose a property type.',
            'project_type.in' => 'Please choose a property type from the list.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Trim before validating so a field of spaces fails `required` rather
        // than passing and storing whitespace.
        $this->merge(collect($this->only([
            'name', 'email', 'phone', 'project_type', 'location', 'project_brief',
        ]))->map(fn ($value) => is_string($value) ? trim($value) : $value)->all());
    }
}
