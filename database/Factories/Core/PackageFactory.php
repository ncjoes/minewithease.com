<?php
declare(strict_types=1);

namespace Database\Factories\Core;

use App\Models\Core\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'name' => $faker->domainWord,
            'description' => $faker->realText(),
            'min_amount' => 50,
            'max_amount' => 1000,
            'split_amount' => 1,
            'min_interest_rate' => $faker->unique()->randomElement(range(1, 5, 1)),
            'max_interest_rate' => $faker->unique()->randomElement(range(6, 10, 1)),
            'interest_interval' => 1,
            'min_duration' => 7,
            'max_duration' => 7,
            'referral_bonus_rate' => "5,2,1",
            'referral_bonus_count' => 5,
            'referral_bonus_release_time' => 0,
            'is_active' => true,
        ];
    }
}