<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use PreferenceTypes;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preference>
 */
class PreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['IT', 'Sports', 'Business', 'Entertainment', 'Health', 'Science', 'Politics', 'Education', 'Finance', 'Lifestyle',
            'Travel', 'Food', 'Environment', 'World News', 'Technology', 'Automobile', 'Fashion', 'Culture', 'History', 'Gaming'];

        $sources = ['NewsAPI', 'OpenNews', 'NewsCred', 'Guardian', 'New York Times', 'BBC News', 'NewsAPI.org'];

        $authors = ['David', 'Liam', 'Arthur', 'Emma', 'Olivia', 'Noah', 'Ava', 'Sophia', 'James', 'Isabella',
            'Ethan', 'Mia', 'Benjamin', 'Charlotte', 'Lucas', 'Amelia', 'Mason', 'Harper', 'Elijah', 'Evelyn'];

        $type = $this->faker->randomElement([PreferenceTypes::SOURCE, PreferenceTypes::CATEGORY, PreferenceTypes::AUTHOR]);

        $name = match ($type) {
            PreferenceTypes::SOURCE => $this->faker->randomElement($sources),
            PreferenceTypes::CATEGORY => $this->faker->randomElement($categories),
            PreferenceTypes::AUTHOR => $this->faker->randomElement($authors),
        };

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'type' => $type,
        ];
    }
}
