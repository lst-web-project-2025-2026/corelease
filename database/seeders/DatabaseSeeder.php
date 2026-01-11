<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Resource;
use App\Models\Setting;
use App\Models\Application;
use App\Models\Maintenance;
use App\Models\Reservation;
use App\Models\Incident;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Global Settings
        Setting::create(["key" => "facility_maintenance", "value" => "0"]);

        // Create Resources first (So we can link them)
        $resources = Resource::factory(100)->create();

        // Fixed Admin
        $fixedAdmin = User::create([
            "name" => "Fixed Admin",
            "email" => "admin@dcrms.com",
            "password" => Hash::make("password"),
            "role" => "Admin",
            "profession" => "System Architect",
            "is_active" => true,
        ]);

        // Extra Admin workload
        Application::factory(15)->create([
            "status" => "Pending",
            "admin_justification" => null,
        ]);

        // Fixed Manager
        $fixedManager = User::create([
            "name" => "Fixed Manager",
            "email" => "manager@dcrms.com",
            "password" => Hash::make("password"),
            "role" => "Manager",
            "profession" => "Data Center Operations",
            "is_active" => true,
        ]);

        // Extra Manager workload
        foreach ($resources->random(20) as $res) {
            Maintenance::factory()->create([
                "resource_id" => $res->id,
                "user_id" => $fixedManager->id,
            ]);
        }

        Reservation::factory(10)->create([
            "resource_id" => $resources->random()->id,
            "status" => "Pending",
            "manager_justification" => null,
        ]);

        // Fixed User
        $fixedUser = User::create([
            "name" => "Fixed User",
            "email" => "user@dcrms.com",
            "password" => Hash::make("password"),
            "role" => "User",
            "profession" => "Cloud Researcher",
            "is_active" => true,
        ]);

        // Extra User activity
        // 5 Active
        Reservation::factory(5)->create([
            "user_id" => $fixedUser->id,
            "status" => "Approved",
            "resource_id" => $resources->random()->id,
        ]);
        // 5 Pending
        Reservation::factory(5)->create([
            "user_id" => $fixedUser->id,
            "status" => "Pending",
            "resource_id" => $resources->random()->id,
        ]);
        // 3 Rejected
        Reservation::factory(3)->create([
            "user_id" => $fixedUser->id,
            "status" => "Rejected",
            "resource_id" => $resources->random()->id,
        ]);
        // 7 Completed
        Reservation::factory(7)->create([
            "user_id" => $fixedUser->id,
            "status" => "Completed",
            "resource_id" => $resources->random()->id,
        ]);

        // Incidents reported by this user on their own active reservations
        $userActiveRes = Reservation::where("user_id", $fixedUser->id)
            ->where("status", "Approved")
            ->get();
        foreach ($userActiveRes->take(3) as $res) {
            Incident::factory()->create([
                "user_id" => $fixedUser->id,
                "resource_id" => $res->resource_id,
                "reservation_id" => $res->id,
            ]);
        }

        // Bulk Data
        User::factory(2)->admin()->create();
        User::factory(10)->manager()->create();
        $randomUsers = User::factory(100)->create();
        Application::factory(20)->create();

        // Random activity for random users
        foreach ($randomUsers->take(70) as $u) {
            Reservation::factory(rand(1, 5))->create([
                "user_id" => $u->id,
                "resource_id" => $resources->random()->id,
            ]);
        }

        $randomReservations = Reservation::where(
            "user_id",
            "!=",
            $fixedUser->id,
        )
            ->inRandomOrder()
            ->limit(12)
            ->get();

        foreach ($randomReservations as $res) {
            Incident::factory()->create([
                "user_id" => $res->user_id,
                "resource_id" => $res->resource_id,
                "reservation_id" => $res->id,
            ]);
        }
    }
}
