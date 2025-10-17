<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class ActiveUserHelper {
    /**
     * @var string The application-level prefix used for active user keys.
     */
    protected const KEY_PREFIX = 'user:last_active:';

    // ... (Keep getFullCachePrefix() helper method as defined previously)
    protected static function getFullCachePrefix(): string {
        $cacheStoreName = config('cache.default');
        $prefix = config("cache.stores.{$cacheStoreName}.prefix") ?? '';
        if (!str_ends_with($prefix, ':') && strlen($prefix) > 0) {
            $prefix .= ':';
        }
        return $prefix;
    }

    // -------------------------------------------------------------------------
    // Core Active User Logic
    // -------------------------------------------------------------------------

    /**
     * Get all currently active users.
     *
     * 🚀 FINAL FIX: Filters the keys array using PHP to strictly ensure only keys
     * containing the 'user:last_active:' string are processed.
     *
     * @return \Illuminate\Support\Collection<User>
     */
    public static function getActiveUsers(): Collection {
        $prefix = self::getFullCachePrefix();

        // 1️⃣ Broad search pattern for all cache keys in DB 1 (including Spatie)
        $searchPattern = $prefix . '*';

        // 2️⃣ Use the 'cache' connection to get all keys (which includes the Spatie key)
        $fullKeys = Redis::connection('cache')->keys($searchPattern);

        if (empty($fullKeys)) {
            return collect();
        }

        // 3️⃣ CRITICAL FILTER: Filter the array to ONLY include keys with the specific prefix.
        // This eliminates the spatie.permission.cache key immediately.
        $userKeys = array_filter($fullKeys, fn(string $key) => str_contains($key, self::KEY_PREFIX));

        if (empty($userKeys)) {
            return collect();
        }

        // 🚀 CRITICAL FIX: Use Str::after() to safely extract the ID.
        // This is much more robust than calculating substr length manually.
        $ids = collect($userKeys)
            ->map(function ($fullKey) {
                // 1. Define the full, combined prefix string we want to remove.
                $searchFor = self::getFullCachePrefix() . self::KEY_PREFIX;

                // 2. Extract the string *after* the full prefix.
                $idString = \Str::after($fullKey, $searchFor);

                return (int) $idString;
            })
            ->filter() // Remove any resulting 0s or nulls, just in case
            ->toArray();

        // dd($ids); // <--- Run this line now! It should show [1]

        // 4️⃣ Fetch users from DB in a single, efficient query.
        return User::whereIn('id', $ids)->get();
    }

    /**
     * Get the count of currently active users without fetching the models.
     *
     * 🚀 FINAL FIX: Uses the same explicit filtering logic for a reliable count.
     *
     * @return int
     */
    public static function countActiveUsers(): int {
        $prefix = self::getFullCachePrefix();

        // 1️⃣ Search for all keys in the cache database
        $searchPattern = $prefix . '*';

        // 2️⃣ Get the full list of keys from the cache connection
        $fullKeys = Redis::connection('cache')->keys($searchPattern);

        // 3️⃣ CRITICAL FILTER: Filter to only include keys that contain the specific prefix.
        $userKeys = array_filter($fullKeys, fn(string $key) => str_contains($key, self::KEY_PREFIX));

        // Return the count of the reliably filtered list
        return count($userKeys);
    }

    // -------------------------------------------------------------------------
    // Utility Methods (Cache-Agnostic and Reliable)
    // -------------------------------------------------------------------------

    /**
     * Check if a specific user is currently active.
     *
     * DEV NOTE: Uses Cache::has() which is reliable because the Cache facade
     * automatically uses the 'cache' connection and handles the prefix internally.
     *
     * @param int $userId
     * @return bool
     */
    public static function isUserActive(int $userId): bool {
        return Cache::has(self::KEY_PREFIX . $userId);
    }

    /**
     * Remove a user from the active list.
     *
     * DEV NOTE: Uses Cache::forget() for consistent removal,
     * typically triggered by a Logout event listener.
     *
     * @param int $userId
     * @return bool
     */
    public static function removeUser(int $userId): bool {
        return Cache::forget(self::KEY_PREFIX . $userId);
    }
}
