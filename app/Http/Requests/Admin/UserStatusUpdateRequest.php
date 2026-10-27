<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStatusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageUsers() ?? false;
    }

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                Rule::in([
                    User::ROLE_ADMIN,
                    User::ROLE_MANAGER,
                    User::ROLE_SUPPORT_AGENT,
                    User::ROLE_USER,
<<<<<<< HEAD
                ])
=======
                ]),
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
            ],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
