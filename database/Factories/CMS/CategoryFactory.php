<?php
declare(strict_types=1);

namespace Database\Factories\CMS;

use App\Models\CMS\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'slug'        => $faker->unique()->slug(2),
            'title'       => $faker->word,
            'description' => $faker->realText(200),
            'cardinality' => 0,
        ];
    }
}
