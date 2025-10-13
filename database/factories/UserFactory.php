<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserForbid;
use App\Models\UserTemporaryPermission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserFactory extends Factory {
    protected $model = User::class;

    public function definition(): array {
        return [
            'name' => fake()->name(),
            'lastname' => $this->faker->optional()->lastName(), // nullable
            'sex' => $this->faker->optional()->randomElement(['Male', 'Female']), // nullable random
            'bday' => $this->faker->optional()->dateTimeBetween('-60 years', '-18 years')?->format('Y-m-d'),

            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Role-Based Access (RBAC only)
     */
    public function rbacOnly() {
        return $this->afterCreating(function (User $user) {
            $role = Role::inRandomOrder()->first() ?? Role::create(['name' => 'member']);
            $user->assignRole('user');
        });
    }

    /**
     * Direct permissions only
     */
    public function directOnly() {
        return $this->afterCreating(function (User $user) {
            $permissions = Permission::inRandomOrder()->limit(3)->get();
            $user->givePermissionTo($permissions);
        });
    }

    /**
     * Forbidden permissions only
     */
    public function forbidOnly() {
        return $this->afterCreating(function (User $user) {
            $permissions = Permission::inRandomOrder()->limit(2)->pluck('name')->toArray();

            foreach ($permissions as $permName) {
                UserForbid::create([
                    'user_id' => $user->id,
                    'permission_name' => $permName,
                ]);
            }
        });
    }

    /**
     * Hybrid: roles + direct + forbidden
     */
    public function hybrid() {
        return $this->afterCreating(function (User $user) {
            // Assign a random role
            $role = Role::inRandomOrder()->first() ?? Role::create(['name' => 'editor']);
            $user->assignRole($role);

            // Add 2 direct permissions
            $directPerms = Permission::inRandomOrder()->limit(2)->get();
            $user->givePermissionTo($directPerms);

            // Add 1 forbidden permission
            $forbid = Permission::inRandomOrder()->first();
            UserForbid::create([
                'user_id' => $user->id,
                'permission_name' => $forbid->name,
            ]);
        });
    }

    /**
     * Temporary permissions (expires soon)
     */
    /**
     * Temporary permissions (expires soon)
     */
    /**
     * Temporary permissions (expires soon)
     */
    /**
     * Temporary permissions (debug mode)
     */
    public function temporary() {
        return $this->afterCreating(function (User $user) {
            $permKeys = \Spatie\Permission\Models\Permission::pluck('name')->toArray();

            if (empty($permKeys)) {
                $permKeys = array_keys(config('permissions.labels', []));
            }

            dump([
                'user_id' => $user->id,
                'permKeys' => $permKeys,
            ]);

            if (empty($permKeys)) {
                dump('❌ No permissions found anywhere!');
                return;
            }

            $randomPerms = \Illuminate\Support\Arr::wrap(
                \Illuminate\Support\Arr::random($permKeys, rand(1, min(3, count($permKeys))))
            );

            dump([
                'chosen_perms' => $randomPerms,
            ]);

            foreach ($randomPerms as $permName) {
                dump("➡ Creating temporary permission: {$permName}");
                \App\Models\UserTemporaryPermission::create([
                    'user_id' => $user->id,
                    'permission_name' => $permName,
                    'expires_at' => \Carbon\Carbon::now()->addDays(rand(1, 7)),
                ]);
            }
        });
    }
}
