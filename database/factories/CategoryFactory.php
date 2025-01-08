<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = \App\Models\Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['income', 'expense']),
            'parent_id' => $this->faker->optional()->randomElement(Category::pluck('id')->toArray()),
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
}
