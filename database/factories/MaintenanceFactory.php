<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maintenance>
 */
class MaintenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate logical dates
        $start = fake()->dateTimeBetween("-2 weeks", "+2 weeks");
        $end = (clone $start)->modify("+" . rand(4, 72) . " hours");

        return [
            "start_date" => $start,
            "end_date" => $end,
            "description" => "Routine maintenance: " . fake()->bs(),
        ];
    }
}
