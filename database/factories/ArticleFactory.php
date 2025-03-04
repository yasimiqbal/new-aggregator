<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['IT', 'Sports', 'Business', 'Entertainment', 'Health', 'Science', 'Politics', 'Education', 'Finance', 'Lifestyle', 'Travel',
            'Food', 'Environment', 'World News', 'Technology', 'Automobile', 'Fashion', 'Culture', 'History', 'Gaming'];
        $sources = ['NewsAPI', 'OpenNews', 'NewsCred', 'Guardian', 'New York Times', 'BBC News', 'NewsAPI.org'];

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'author' => $this->faker->name,
            'category' => $this->faker->randomElement($categories),
            'source' => $this->faker->randomElement($sources),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
