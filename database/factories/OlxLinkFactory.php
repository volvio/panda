<?php

namespace Database\Factories;

use App\Models\OlxLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class OlxLinkFactory extends Factory
{
    protected $model = OlxLink::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
            'olx_id' => $this->faker->numberBetween(100000000, 999999999),
            'is_price_update' => 0,
        ];
    }
}
