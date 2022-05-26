<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    protected $model = Album::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cats = [
            'abstract', 'animals', 'business', 'cats', 'city', 'food', 'nightlife', 'fashion', 'people', 'nature', 'sports', 'technics', 'transport'
        ];

        // $user = \App\Models\User::inRandomOrder()->first();
        return [
            'album_name' => $this->faker->text(60),
            'album_thumb' => $this->faker->imageUrl(640, 480, $this->faker->randomElement($cats)),
            'description' => $this->faker->text(120),
            'created_at' => $this->faker->dateTime(),
            'user_id' => \App\Models\User::factory() // $user->id,
        ];
    }
}
