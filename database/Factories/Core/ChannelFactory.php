<?php
declare(strict_types=1);

namespace Database\Factories\Core;

use App\Models\Core\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Channel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = $this->faker;

        return [
            'name'                       => $faker->sentence,
            'website'                    => $faker->url,
            'min_amount'                 => 100,
            'max_amount'                 => 1000000,
            'split_amount'               => 1,
            'description'                => $faker->realText(),
            'payment_wallet'             => $address = $faker->sha1,
            'wallet_address_format'      => $address,
            'wallet_address_placeholder' => $faker->sentence,
            'is_active'                  => true,
            'for_inflow'                 => true,
            'for_outflow'                => true,
        ];
    }
}

