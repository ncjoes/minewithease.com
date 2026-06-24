<?php
declare(strict_types=1);

namespace Database\Factories\CMS;

use App\Models\CMS\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'slug'         => $faker->unique()->slug(2),
            'cardinality'  => 0,
            'title'        => $faker->sentence,
            'summary'      => $faker->realText(400),
            'content'      => $faker->realText(2000),
            'cover_photo'  => $faker->unique()->imageUrl(1900, 583, 'business', true),
            'thumb_photo'  => $faker->unique()->imageUrl(600, 400, 'business', true),
            'is_published' => true,
        ];
    }
}
