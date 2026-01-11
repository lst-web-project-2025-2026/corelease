<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([
            "Pending",
            "Approved",
            "Rejected",
            "Completed",
        ]);
        $start = fake()->dateTimeBetween("-1 month", "+1 month");
        $end = (clone $start)->modify("+" . rand(1, 14) . " days");

        return [
            "user_id" => \App\Models\User::factory(),
            "resource_id" => \App\Models\Resource::factory(),
            "start_date" => $start,
            "end_date" => $end,
            "user_justification" => "Research project: " . fake()->bs(),
            "status" => $status,
            // Business Logic:
            "manager_justification" => match ($status) {
                "Pending" => null,
                "Approved",
                "Completed"
                    => "Reservation confirmed for requested period.",
                "Rejected"
                    => "Rejected: Resource is reserved for higher priority maintenance.",
            },
            "configuration" => ["os" => "Ubuntu 22.04", "backup" => true],
        ];
    }
}
