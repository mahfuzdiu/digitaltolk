<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locales = ['en', 'fr', 'de', 'es', 'pt'];
        $tags = ['mobile', 'web', 'ios', 'android', 'seo', 'admin', 'v2'];

        return [
            'translation_key' => 'key.' . Str::ulid(),
            'locale' => fake()->randomElement($locales),
            'translated_value' => fake()->sentence(6),
            // We return a PHP array; the Model Cast or Seeder will handle conversion
            'context_tag' => fake()->randomElements($tags, rand(1, 3)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
