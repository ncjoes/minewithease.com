<?php
declare(strict_types=1);

namespace Database\Factories\CMS;

use App\Models\CMS\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'cardinality' => 0,
            'question'    => $faker->sentence(8),
            'answer'      => $faker->sentences(5, true),
        ];
    }
}
