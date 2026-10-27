<?php

namespace Database\Factories;

<<<<<<< HEAD
=======
use App\Models\ComplaintCategory;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
<<<<<<< HEAD
 * @extends Factory<\App\Models\ComplaintCategory>
=======
 * @extends Factory<ComplaintCategory>
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
 */
class ComplaintCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Service', 'Product', 'Feedback', 'Other']).' '.fake()->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => fake()->randomElement(['service', 'product', 'feedback', 'other']),
            'description' => fake()->sentence(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 99),
        ];
    }
}
