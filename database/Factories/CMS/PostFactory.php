<?php
declare(strict_types=1);

namespace Database\Factories\CMS;

use App\Models\CMS\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'slug'         => $faker->unique()->slug(),
            'title'        => $faker->sentence,
            'summary'      => $faker->realText(150),
            'content'      => $faker->realText(4000),
            'cover_photo'  => $faker->unique()->imageUrl(1900, 583, 'business', true),
            'thumb_photo'  => $faker->unique()->imageUrl(600, 400, 'business', true),
            'published_at' => $faker->dateTime,
        ];
    }
}
