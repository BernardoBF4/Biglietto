<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'eve_end_datetime' => Carbon::parse(fake()->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
            'eve_start_datetime' => Carbon::parse(fake()->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
            'eve_status' => fake()->boolean(),
            'eve_title' => fake()->name(),
        ];
    }

    public function withEndDateSmallerThanStartDate()
    {
        return $this->state(function () {
            return [
                'eve_end_datetime' => Carbon::parse(fake()->dateTimeBetween('+1 day', '+2 days'))->format('Y-m-d H:i:s'),
                'eve_start_datetime' => Carbon::parse(fake()->dateTimeBetween('+3 day', '+4 days'))->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function withStatus($status)
    {
        return $this->state(function () use ($status) {
            return [
                'eve_status' => $status,
            ];
        });
    }
}
