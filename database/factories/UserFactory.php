<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserForbid;
// DEV NOTE: Re-added import as UserTemporaryPermission is used in the `temporary` state.
use App\Models\UserTemporaryPermission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserFactory extends Factory {
    protected $model = User::class;

    // DEV NOTE: Define the available roles as a static property for easy access and modification.
    protected static array $rbacRoles = ['Admin', 'Editor', 'User'];

    public function definition(): array {
        return [
            'name' => $this->faker->name(),
            'lastname' => $this->faker->optional()->lastName(),
            'sex' => $this->faker->optional()->randomElement(['Male', 'Female']),
            'bday' => $this->faker->optional()->dateTimeBetween('-60 years', '-18 years')?->format('Y-m-d'),
            'status' => 'active',
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // --- ABSOLUTE CONSTRAINT ENFORCEMENT ---

    /**
     * Configure the factory.
     * DEV NOTE: This method ensures ALL created users are assigned a random role.
     * It runs immediately after the model is created, satisfying the absolute requirement.
     */
    public function configure() {
        return $this->afterCreating(function (User $user) {
            // Check if the user already has a role assigned by a specific state (e.g., hybrid, rbacOnly)
            // DEV NOTE: Using the `fresh()` method ensures we check the latest database state.
            if ($user->fresh()->roles->isEmpty()) {
                // If no role is found, assign a random one from the list.
                $randomRoleName = Arr::random(static::$rbacRoles);
                $user->assignRole($randomRoleName);
            }
        });
    }

    // --- FACTORY STATES (Cleaned up) ---

    // In Database\Factories\UserFactory.php

    /**
     * Role-Based Access (RBAC only): Assigns a random role from the defined list.
     * DEV NOTE: This state is used when explicitly creating users based only on the RBAC model.
     */
    public function rbacOnly() {
        $randomRoleName = Arr::random(static::$rbacRoles);

        return $this->afterCreating(function (User $user) use ($randomRoleName) {
            // Explicitly assign a random role.
            $user->assignRole($randomRoleName);

            // DEV NOTE: You could optionally remove any direct permissions or forbids here
            // if this state absolutely must prevent those other types of access.
        });
    }

    /**
     * Direct permissions only
     * DEV NOTE: Assigning direct permissions here is fine, but the user will still get a random role
     * from the `configure` method, as required.
     */
    public function directOnly() {
        return $this->afterCreating(function (User $user) {
            $permissions = Permission::inRandomOrder()->limit(3)->get();
            $user->givePermissionTo($permissions);
        });
    }

    /**
     * Hybrid: roles + direct + forbidden
     * DEV NOTE: Removed the role assignment from here, as the `configure` method will handle
     * assigning a role if the user somehow doesn't have one (though it should be the last thing checked).
     * However, for control, let's keep the explicit role assignment in this state.
     */
    public function hybrid() {
        // Assign the role explicitly here to override the random assignment in `configure` if necessary
        $randomRoleName = Arr::random(static::$rbacRoles);

        return $this->afterCreating(function (User $user) use ($randomRoleName) {
            // Assign a random role explicitly for the Hybrid state
            $user->assignRole($randomRoleName);

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
     * Forbidden permissions only
     */
    public function forbidOnly() {
        return $this->afterCreating(function (User $user) {
            // DEV NOTE: No major change, this is a clean way to handle a custom forbidding model.
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
     * Temporary permissions
     */
    public function temporary() {
        return $this->afterCreating(function (User $user) {
            // DEV NOTE: Cleaned up redundant doc blocks and unused `dump` calls.
            // The logic here is complex due to the fallback to config, but maintained for integrity.

            $permKeys = Permission::pluck('name')->toArray();

            if (empty($permKeys)) {
                // Fallback to config if the Permission table is empty
                $permKeys = array_keys(config('permissions.labels', []));
            }

            if (empty($permKeys)) {
                // return early if no permissions are available
                return;
            }

            $randomPerms = Arr::wrap(
                Arr::random($permKeys, rand(1, min(3, count($permKeys))))
            );

            foreach ($randomPerms as $permName) {
                \App\Models\UserTemporaryPermission::create([
                    'user_id' => $user->id,
                    'permission_name' => $permName,
                    'expires_at' => Carbon::now()->addDays(rand(1, 7)),
                ]);
            }
        });
    }
}
