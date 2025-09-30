<?php

namespace Database\Factories;

use App\Models\OlxPrice;
use App\Models\OlxLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class OlxPriceFactory extends Factory
{
    protected $model = OlxPrice::class;

    public function definition(): array
    {
        return [
            'olx_link_id' => OlxLink::factory(),
            'price' => $this->faker->numberBetween(100, 100000),
            'currency' => 'UAH',
        ];
    }
}
