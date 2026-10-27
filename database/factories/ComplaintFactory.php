<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
use Illuminate\Support\Str;
=======
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9

/**
 * @extends Factory<Complaint>
 */
class ComplaintFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'complaint_category_id' => ComplaintCategory::factory(),
            'assigned_to' => null,
            'reference' => 'CMP-'.now()->format('Y').'-'.str_pad((string) fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'title' => fake()->sentence(5),
            'description' => fake()->paragraphs(3, true),
            'status' => Complaint::STATUS_PENDING,
            'priority' => 'normal',
            'submitted_at' => now(),
            'first_admin_action_at' => null,
            'resolved_at' => null,
            'withdrawn_at' => null,
            'withdrawal_reason' => null,
        ];
    }
}
