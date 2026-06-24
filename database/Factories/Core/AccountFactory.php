<?php
declare(strict_types=1);

namespace Database\Factories\Core;

use App\Models\Core\Account;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class AccountFactory extends Factory
{
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_address' => fake()->macAddress,
            'is_active' => true,
        ];
    }
}
