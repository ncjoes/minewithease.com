<?php
declare(strict_types=1);

namespace Database\Factories\CMS;

use App\Models\CMS\Testimony;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Testimony::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'name'      => $faker->unique()->name,
            'statement' => $faker->realText(400),
            'status'    => Testimony::S_PUBLISHED,
        ];
    }
}