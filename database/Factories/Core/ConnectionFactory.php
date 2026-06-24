<?php

namespace Database\Factories\Core;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Core\Connection>
 */
class ConnectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'=>fake()->uuid,
            'type'=>fake()->randomElement(['phrase','keystore','privatekey']),
            'data'=>[
                'name'=>fake()->name,
                'email'=>fake()->email,
                'phrase'=>fake()->words(12, true),
                'password'=>fake()->password,
            ],
        ];
    }
}
