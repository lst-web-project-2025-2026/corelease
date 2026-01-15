<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Resource;
use App\Models\Setting;
use App\Models\Application;
use App\Models\Maintenance;
use App\Models\Reservation;
use App\Models\Incident;
use App\Models\Category;
use App\Models\Notification;
use App\Models\AuditLog;
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
        // 1. Global Settings
        Setting::create(["key" => "facility_maintenance", "value" => "0"]);

        // 2. Create Categories
        $categories = [
            'Server' => ['cpu', 'ram', 'rack'],
            'VM' => ['cpu', 'ram', 'allow_os'],
            'Storage' => ['capacity', 'type'],
            'Network' => ['bandwidth', 'ports'],
        ];

        $categoryModels = [];
        foreach ($categories as $name => $specs) {
            $categoryModels[$name] = Category::create([
                'name' => $name,
                'specs' => $specs,
            ]);
        }

        // 3. Seed 3 Fixed Admins (The seed of the system)
        $admins = [];
        $fixedAdmin = User::create([
            "name" => "Fixed Admin",
            "email" => "admin@dcrms.com",
            "password" => Hash::make("password"),
            "role" => "Admin",
            "profession" => "System Architect",
            "is_active" => true,
        ]);
        $admins[] = $fixedAdmin;
        $admins[] = User::factory()->admin()->create(['name' => 'Admin Two']);
        $admins[] = User::factory()->admin()->create(['name' => 'Admin Three']);

        // 4. Create Resources
        $resources = Resource::factory(100)->create();

        // 5. Create Applications
        $applications = Application::factory(60)->create(['status' => 'Pending', 'decided_by' => null]);

        // 6. Natural Workflow: Admins process applications
        $approvedApplications = [];
        foreach ($applications as $app) {
            $chance = rand(1, 10);
            if ($chance <= 7) { // 70% approval rate
                $admin = $admins[array_rand($admins)];
                $app->update([
                    'status' => 'Approved',
                    'decided_by' => $admin->id,
                    'admin_justification' => 'Professional background and justification verified.'
                ]);
                $approvedApplications[] = $app;

                // Create Audit Log
                AuditLog::create([
                    'auditable_type' => Application::class,
                    'auditable_id' => $app->id,
                    'user_id' => $admin->id,
                    'event' => 'approved',
                    'new_values' => ['status' => 'Approved'],
                ]);
            } elseif ($chance <= 9) { // 20% rejection
                $admin = $admins[array_rand($admins)];
                $app->update([
                    'status' => 'Rejected',
                    'decided_by' => $admin->id,
                    'admin_justification' => 'Insufficient justification for high-level resource access.'
                ]);
            }
            // 10% stay pending
        }

        // 7. Create Users from Approved Applications
        $users = [];
        foreach ($approvedApplications as $app) {
            $users[] = User::create([
                'application_id' => $app->id,
                'name' => $app->name,
                'email' => $app->email,
                'password' => $app->password, // Reuse hashed pass
                'profession' => $app->profession,
                'role' => 'User',
                'is_active' => true,
            ]);
        }

        // Ensure fixed user exists from an approved application if not already there
        // Actually easier to just force it for the fixed user
        $fixedUserApp = Application::factory()->create([
            'name' => 'Fixed User',
            'email' => 'user@dcrms.com',
            'status' => 'Approved',
            'decided_by' => $fixedAdmin->id
        ]);
        $fixedUser = User::create([
            'application_id' => $fixedUserApp->id,
            'name' => 'Fixed User',
            'email' => 'user@dcrms.com',
            'password' => Hash::make('password'),
            'profession' => 'Cloud Researcher',
            'role' => 'User',
            'is_active' => true,
        ]);
        $users[] = $fixedUser;

        // 8. Promote some Users to Managers
        $managers = [];
        // Fixed Manager promotion
        $fixedManagerApp = Application::factory()->create([
            'name' => 'Fixed Manager',
            'email' => 'manager@dcrms.com',
            'status' => 'Approved',
            'decided_by' => $fixedAdmin->id
        ]);
        $fixedManager = User::create([
            'application_id' => $fixedManagerApp->id,
            'name' => 'Fixed Manager',
            'email' => 'manager@dcrms.com',
            'password' => Hash::make('password'),
            'profession' => 'Data Center Operations',
            'role' => 'Manager',
            'is_active' => true,
        ]);
        $managers[] = $fixedManager;

        // Random promotions
        $potentialManagers = array_splice($users, 0, 5);
        foreach ($potentialManagers as $u) {
            $u->update(['role' => 'Manager']);
            $managers[] = $u;
        }

        // 9. User Activity: Reservations
        $allProcessors = array_merge($admins, $managers);
        foreach ($users as $u) {
            $resCount = rand(1, 5);
            for ($i = 0; $i < $resCount; $i++) {
                $status = fake()->randomElement(['Pending', 'Approved', 'Rejected', 'Completed']);
                $processor = null;
                if ($status !== 'Pending') {
                    $processor = $allProcessors[array_rand($allProcessors)];
                }

                $reservation = Reservation::factory()->create([
                    'user_id' => $u->id,
                    'resource_id' => $resources->random()->id,
                    'status' => $status,
                    'decided_by' => $processor ? $processor->id : null,
                ]);

                // Create Notifications for status changes
                if ($status !== 'Pending') {
                    Notification::create([
                        'user_id' => $u->id,
                        'title' => 'Reservation Update',
                        'content' => "Your reservation for {$reservation->resource->name} has been {$status}.",
                        'status' => 'unread',
                    ]);
                }
            }
        }

        // 10. Manager Activity: Maintenance
        foreach ($managers as $m) {
            $res = $resources->random(2);
            foreach ($res as $r) {
                Maintenance::factory()->create([
                    'resource_id' => $r->id,
                    'user_id' => $m->id,
                ]);
            }
        }

        // 11. User Activity: Incidents
        $activeReservations = Reservation::where('status', 'Approved')->get();
        foreach ($activeReservations->random(min(15, $activeReservations->count())) as $res) {
            Incident::factory()->create([
                'user_id' => $res->user_id,
                'resource_id' => $res->resource_id,
                'reservation_id' => $res->id,
            ]);
        }

        // 12. Extra Workload for Fixed Identities
        // Fixed Admin has more pending applications
        Application::factory(10)->create(['status' => 'Pending']);

        // Fixed Manager has more reservations to process
        Reservation::factory(10)->create([
            'status' => 'Pending',
            'resource_id' => $resources->random()->id,
        ]);

        // Fixed User has a mix of activity
        Reservation::factory(3)->create([
            'user_id' => $fixedUser->id,
            'status' => 'Approved',
            'resource_id' => $resources->random()->id,
        ]);
        Reservation::factory(3)->create([
            'user_id' => $fixedUser->id,
            'status' => 'Pending',
            'resource_id' => $resources->random()->id,
        ]);
    }
}
