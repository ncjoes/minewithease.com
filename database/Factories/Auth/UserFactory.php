<?php
declare(strict_types=1);

namespace Database\Factories\Auth;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'uuid'             => strtoupper(substr(fake()->unique()->hexColor, 1)), //User::generateUUID(),
            'email'            => fake()->unique()->safeEmail,
            'phone'            => fake()->unique()->numerify('0803#######'),
            'first_name'       => fake()->firstName,
            'last_name'        => fake()->lastName,
            'status'           => User::S_ACTIVATED,
            'password'         => 'secret',
            'account_settings' => User::defaultSettings(),
            'remember_token'   => Str::random(16),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
