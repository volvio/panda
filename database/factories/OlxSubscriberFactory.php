<?php

namespace Database\Factories;

use App\Models\OlxSubscriber;
use App\Models\OlxLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OlxSubscriberFactory extends Factory
{
    protected $model = OlxSubscriber::class;

    public function definition(): array
    {
        return [
            'olx_link_id' => OlxLink::factory(),
            'email' => $this->faker->safeEmail(),
            'confirmation_token' => Str::uuid(),
            'confirmed_at' => Carbon::now(),
        ];
    }
}
