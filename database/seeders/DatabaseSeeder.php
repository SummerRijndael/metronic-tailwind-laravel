<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call(RolesAndPermissionsSeeder::class);
        //User::factory()->count(10)->create();

        // Create 5 RBAC-only users
        User::factory()->count(1)->rbacOnly()->create();

        // Create 3 direct-permission only users
        User::factory()->count(1)->directOnly()->create();

        // Create 2 forbidden-permission only users
        User::factory()->count(1)->forbidOnly()->create();

        // Create 2 hybrid users (role + direct + forbidden)
        User::factory()->count(1)->hybrid()->create();

        // Create 2 temporary permission users
        User::factory()->count(1)->temporary()->create();
    }
}
