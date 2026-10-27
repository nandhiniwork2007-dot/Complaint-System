<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComplaintCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach ([
            ['name' => 'Service', 'type' => 'service'],
            ['name' => 'Product', 'type' => 'product'],
            ['name' => 'Feedback', 'type' => 'feedback'],
            ['name' => 'Other', 'type' => 'other'],
        ] as $index => $category) {
            ComplaintCategory::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'type' => $category['type'],
<<<<<<< HEAD
                    'description' => $category['name'] . ' related complaints.',
=======
                    'description' => $category['name'].' related complaints.',
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
