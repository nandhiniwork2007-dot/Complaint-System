<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComplaintAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStaff() ?? false;
    }

    public function rules(): array
    {
        return [
            'assigned_to' => [
                'nullable',
<<<<<<< HEAD
                Rule::exists('users', 'id')->where(fn($query) => $query->whereIn('role', [
=======
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', [
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
                    User::ROLE_ADMIN,
                    User::ROLE_MANAGER,
                    User::ROLE_SUPPORT_AGENT,
                ])),
            ],
        ];
    }
}
