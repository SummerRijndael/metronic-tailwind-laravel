<?php

namespace App\Helpers;

use App\Models\SystemActivityLog;
use App\Enums\ActivityCategory;
use App\Enums\ActivityAction;
use App\Enums\ActivityLevel;
use App\Enums\ActivityTarget;
use App\Enums\ActivitySubject;
use Illuminate\Support\Facades\Auth;

/**
 * --------------------------------------------------------------
 * 🔥 ActivityLogger Helper (Fluent & Enum-Aware, Full Version)
 * --------------------------------------------------------------
 *
 * Provides a structured, developer-friendly, and high-performance
 * way to log user or system actions for audit & analytics.
 */
class ActivityLogger {
    protected ?string $category = null;
    protected ?string $action = null;
    protected ?string $message = null;
    protected ?array $meta = [];
    protected $user = null;
    protected ?string $source = null;

    // NEW fields for enhanced logging
    protected ?string $level = null;
    protected ?string $target = null;
    protected ?string $subject = null;

    /**
     * Create a new instance with category.
     */
    public static function category(ActivityCategory|string|null $category): self {
        $instance = new self;
        $instance->category = $category instanceof ActivityCategory ? $category->value : $category;
        return $instance;
    }

    /**
     * Define the action being logged.
     */
    public function action(ActivityAction|string|null $action): self {
        $this->action = $action instanceof ActivityAction ? $action->value : $action;
        return $this;
    }

    /**
     * Optional human-readable message/description.
     */
    public function message(?string $message): self {
        $this->message = $message;
        return $this;
    }

    /**
     * Optional contextual metadata.
     */
    public function meta(?array $meta): self {
        if (is_array($meta)) {
            $this->meta = array_merge($this->meta ?? [], $meta);
        }
        return $this;
    }

    /**
     * Specify a user explicitly (optional).
     */
    public function user($user): self {
        $this->user = $user;
        return $this;
    }

    /**
     * Tag source (e.g., web, api, console, scheduler).
     */
    public function source(?string $source): self {
        $this->source = $source;
        return $this;
    }

    /**
     * Set activity level (info, warning, critical, etc.).
     */
    public function level(ActivityLevel|string|null $level): self {
        $this->level = $level instanceof ActivityLevel ? $level->value : $level;
        return $this;
    }

    /**
     * Set the target of the activity (self, other user, system, etc.).
     */
    public function target(ActivityTarget|string|null $target): self {
        $this->target = $target instanceof ActivityTarget ? $target->value : $target;
        return $this;
    }

    /**
     * Set the subject of the activity (user, role, permission, etc.).
     */
    public function subject(ActivitySubject|string|null $subject): self {
        $this->subject = $subject instanceof ActivitySubject ? $subject->value : $subject;
        return $this;
    }

    /**
     * Save the activity to the database.
     */
    public function log(): void {
        $user = $this->user ?? Auth::user();

        $meta = array_merge($this->meta ?? [], [
            'ip' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        SystemActivityLog::create([
            'user_id'     => $user?->id,
            'category'    => $this->category ?? ActivityCategory::SYSTEM->value,
            'action'      => $this->action ?? 'unspecified',
            'message' => $this->message,
            'meta'        => $meta,
            'ip_address'  => $meta['ip'] ?? null,
            'user_agent'  => $meta['user_agent'] ?? null,
            'source'      => $this->source ?? 'web',
            'level'       => $this->level ?? ActivityLevel::INFO->value,
            'target'      => $this->target ?? ActivityTarget::SELF->value,
            'subject'     => $this->subject ?? ActivitySubject::USER->value,
        ]);
    }
}

/**
 * --------------------------------------------------------------
 * 🧩 Legacy Bridge (Backwards Compatibility)
 * --------------------------------------------------------------
 */
if (!function_exists('logUserActivity')) {
    function logUserActivity(
        string $action,
        ?string $description = null,
        ?array $meta = null,
        $user = null,
        bool $skipAuthCheck = false
    ): void {
        $user = $user ?? Auth::user();

        if (!$user && !$skipAuthCheck) {
            return; // Avoid logging when user is required but missing
        }

        ActivityLogger::category(ActivityCategory::APP)
            ->action($action)
            ->message($description)
            ->meta($meta)
            ->user($user)
            ->source('legacy')
            ->log();
    }
}
