<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStaff() ?? false;
    }

    protected function prepareForValidation(): void
    {
<<<<<<< HEAD
        if ($this->filled('name') && !$this->filled('slug')) {
=======
        if ($this->filled('name') && ! $this->filled('slug')) {
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
            $this->merge(['slug' => Str::slug($this->string('name')->value())]);
        }
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('complaint_categories', 'name')->ignore($categoryId)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('complaint_categories', 'slug')->ignore($categoryId)],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
