<?php
declare(strict_types=1);

namespace Database\Factories\CMS;

use App\Models\CMS\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title'        => fake()->sentence(3, false),
            'description'  => fake()->sentence(10,false),
            'action_url'   => fake()->url,
            'action_label' => fake()->sentence(2,false),
        ];
    }
}
