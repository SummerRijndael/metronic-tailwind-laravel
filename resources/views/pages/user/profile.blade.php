@extends('layouts.main.base')
@section('content')
    <!-- Main Grid Container -->
    <div class="grid min-h-screen gap-4 lg:grid-cols-3">

        <!-- A: LEFT SIDEBAR -->
        <div class="space-y-6 lg:col-span-1">
            <div class="flex h-full flex-col rounded-xl border border-border bg-background shadow-md">

                <!-- Sidebar Header -->
                <div class="kt-card-header border-b border-border p-4">
                    <h2 class="kt-card-title">Personal Info</h2>
                </div>

                <!-- Sidebar Content (flexible) -->
                <div class="kt-card-content flex-1 overflow-y-auto p-4">
                    <div class="flex flex-col items-center gap-3 text-center">
                        <!-- Avatar -->
                        <x-image-uploader name="avatar" static="true" id="imgupld" size="lg" align="center"
                            :preview="$user->avatar_url" />

                        <!-- Name & Email -->
                        <div>
                            <div class="text-lg font-semibold text-gray-100">
                                {{ $user->name }}{{ $user->lastname ? ' ' . $user->lastname : '' }}
                            </div>

                            <div class="gap-1.25 flex items-center">
                                <i class="ki-filled ki-sms text-sm text-muted-foreground">
                                </i>
                                <a class="text-sm text-secondary-foreground hover:font-medium hover:text-primary"
                                    href="mailto:{{ $user->email }}">
                                    {{ $user->email }}
                                </a>

                            </div>
                        </div>

                        <!-- Status Badge -->

                        @if ($user->is_online)
                            <span class="kt-badge kt-badge-outline kt-badge-success">
                                Online
                            </span>
                        @else
                            <span class="kt-badge kt-badge-outline kt-badge-mono">
                                Offline
                            </span>
                        @endif
                    </div>

                    <!-- Info Rows -->
                    <div class="mt-6 divide-y divide-border">
                        @php
                            $info = [
                                'Full name' => $user->name . ' ' . $user->lastname,
                                'Birthday' => $user->bday ? $user->bday->format('F j, Y') : 'Not specified',
                                'Gender' => $user->sex ? $user->sex : 'Not specified',
                                'Mobile' => $user->mobile ? $user->mobile : 'Not specified',
                                'Address' => 'You don’t have any address yet',
                            ];
                        @endphp

                        @foreach ($info as $label => $value)
                            <div class="grid grid-cols-2 gap-2 py-2">
                                <dt class="text-sm font-medium text-gray-100">{{ $label }}</dt>
                                <dd class="text-muted text-sm">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar Footer -->
                <div class="kt-card-footer justify-center border-t border-border p-4">
                    <a class="kt-link" href="{{ route('profile_settings.show') }}#basic_settings">Edit Personal Details</a>
                </div>
            </div>
        </div>

        <!-- B & C: RIGHT STACKED COLUMNS -->
        <div class="flex flex-col gap-4 lg:col-span-2">

            <!-- B: TOP RIGHT CONTAINER -->
            <div class="kt-card min-w-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        Account details
                    </h3>

                </div>
                <div class="kt-card-table kt-scrollable-x-auto pb-3 shadow-md">
                    <table class="kt-table align-middle text-sm text-muted-foreground">
                        <tbody>
                            <tr>
                                <td class="min-w-36 py-2 font-normal text-gray-600">
                                    Role:
                                </td>
                                <td class="w-full min-w-72 py-2 font-normal text-foreground">
                                    {{ $roles->first() }}

                                </td>
                                <td class="min-w-24 py-2 text-end">

                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 font-normal text-secondary-foreground">
                                    Account created:
                                </td>
                                <td class="py-2 font-normal text-foreground">
                                    {{ $user->created_at->format('l, F j, Y g:i A') }}
                                </td>
                                <td class="py-2 text-end">

                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 font-normal text-gray-600">
                                    Last Updated
                                </td>
                                <td class="py-2 font-normal text-foreground">
                                    {{ $user->updated_at->format('l, F j, Y g:i A') }}
                                </td>
                                <td class="py-2 text-end">

                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 font-normal text-secondary-foreground">
                                    Permissions
                                </td>
                                <td class="py-3 text-secondary-foreground">
                                    <div class="flex flex-col gap-3">

                                        <!-- 🔹 Permission Legend -->
                                        <div class="flex flex-row flex-wrap items-center gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="kt-badge kt-badge-primary"
                                                    style="width: 16px; height: 16px;"></span>
                                                <span class="text-muted text-sm">Role-Based</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="kt-badge kt-badge-success"
                                                    style="width: 16px; height: 16px;"></span>
                                                <span class="text-muted text-sm">Direct Permission</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="kt-badge kt-badge-destructive"
                                                    style="width: 16px; height: 16px;"></span>
                                                <span class="text-muted text-sm">Blocked</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="kt-badge kt-badge-warning"
                                                    style="width: 16px; height: 16px;"></span>
                                                <span class="text-muted text-sm">Temporary</span>
                                            </div>
                                        </div>

                                        <!-- 🔸 Permission Badges -->
                                        <div class="flex flex-wrap gap-2.5">
                                            @foreach ($permissions as $perm)
                                                @php
                                                    $colorClass = match ($perm['source']) {
                                                        'forbid' => 'kt-badge-destructive',
                                                        'direct' => 'kt-badge-success',
                                                        default => 'kt-badge-primary',
                                                    };

                                                    $dotColor = match ($perm['source']) {
                                                        'forbid' => 'bg-danger',
                                                        'direct' => 'bg-success',
                                                        default => 'bg-primary',
                                                    };

                                                    if ($perm['temporary']) {
                                                        $colorClass = 'kt-badge-warning';
                                                        $dotColor = 'bg-warning';
                                                    }
                                                @endphp

                                                <div class="d-inline-block mb-1 me-1">
                                                    <span class="kt-badge kt-badge-outline {{ $colorClass }}"
                                                        data-kt-tooltip="#tooltip_{{ Str::slug($perm['name'], '_') }}"
                                                        data-kt-tooltip-trigger="hover" data-kt-tooltip-placement="top"
                                                        style="cursor: pointer;">
                                                        {{ $perm['label'] }}
                                                    </span>

                                                    <!-- 🧠 Tooltip -->
                                                    <div id="tooltip_{{ Str::slug($perm['name'], '_') }}"
                                                        class="kt-popover max-w-56">
                                                        <div
                                                            class="kt-popover-header fw-bold d-flex align-items-center text-gray-800">
                                                            <span
                                                                class="d-inline-block rounded-circle {{ $dotColor }} me-2"
                                                                style="width: 8px; height: 8px;"></span>
                                                            {{ $perm['label'] }}
                                                        </div>
                                                        <div class="kt-popover-content small text-gray-600 shadow-md">
                                                            <div><strong>Source:</strong>
                                                                {{ ucfirst($perm['source']) }}</div>
                                                            <div><strong>Category:</strong>
                                                                {{ $perm['category'] ?? 'Misc' }}</div>
                                                            @if ($perm['temporary'])
                                                                <div><strong>Status:</strong> ⏳ Temporary</div>
                                                            @elseif ($perm['forbidden'])
                                                                <div><strong>Status:</strong> ❌ Forbidden</div>
                                                            @else
                                                                <div><strong>Status:</strong> ✅ Active</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </td>

                                <td class="py-3 text-end">

                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 font-normal text-secondary-foreground">
                                    Email verfied at
                                </td>
                                <td class="py-4 font-normal text-foreground">
                                    {{ $user->email_verified_at ? $user->email_verified_at->format('l, F j, Y g:i A') : 'Not verified' }}
                                </td>
                                <td class="py-4 text-end">
                                    @if (!$user->email_verified_at)
                                        <button type="button" class="kt-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-settings" aria-hidden="true">
                                                <path
                                                    d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                                                </path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            Resend Verification
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- C: BOTTOM RIGHT CONTAINER -->
            <div class="kt-card min-w-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        Basic Settings
                    </h3>

                </div>
                <div class="kt-card-table kt-scrollable-x-auto pb-3 shadow-md">
                    <table class="kt-table align-middle text-sm text-muted-foreground">
                        <tbody>
                            <tr>
                                <td class="min-w-36 py-2 font-normal text-secondary-foreground">
                                    Email
                                </td>
                                <td class="min-w-60 py-2">
                                    <a class="text-sm font-normal text-foreground hover:text-primary"
                                        href="mailto:{{ $user->email }}">
                                        {{ $user->email }}
                                    </a>
                                </td>
                                <td class="max-w-16 py-2 text-end">
                                    <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary"
                                        href="{{ route('profile_settings.show') }}#auth_email">
                                        <i class="ki-filled ki-notepad-edit">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 font-normal text-gray-600">
                                    Last Password Update
                                </td>
                                <td class="py-2 font-normal text-secondary-foreground">
                                    {{ $user->password_changed_at ? $user->password_changed_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="py-2 text-end">
                                    <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary"
                                        href="{{ route('profile_settings.show') }}#auth_password">
                                        <i class="ki-filled ki-notepad-edit">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3.5 font-normal text-gray-600">
                                    Two Factor Authentication
                                </td>
                                <td class="py-3.5 font-normal text-secondary-foreground">
                                    {!! $user->two_factor_confirmed_at
                                        ? '<span class="kt-badge kt-badge-outline kt-badge-success"> Active</span> | ' .
                                            $user->two_factor_confirmed_at->format('F j, Y')
                                        : 'To be set' !!}
                                </td>
                                <td class="py-3 text-end">
                                    @if (!$user->two_factor_confirmed_at)
                                        <a class="kt-link kt-link-underlined kt-link-dashed" href="#">
                                            Setup
                                        </a>
                                    @else
                                        <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary"
                                            href="{{ route('profile_settings.show') }}#auth_two_factor">
                                            <i class="ki-filled ki-notepad-edit">
                                            </i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 font-normal text-gray-600">

                                </td>
                                <td class="py-0.5">
                                    <a class="kt-link" href="#">View All Active Sessions</a>
                                </td>
                                <td class="py-2 text-end">

                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>


            <div class="kt-card mt-4 w-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">Recent Activity Logs</h3>
                </div>
                <div class="boder border-boder p-4 shadow-md">
                    <div
                        class="custom-scrollbar max-h-[500px] overflow-y-auto rounded-md border border-border bg-background">
                        <table class="kt-table kt-table-border min-w-full divide-y divide-border text-sm">
                            <thead class="sticky top-0 bg-background">
                                <tr>
                                    <th
                                        class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                        Timestamp</th>
                                    <th
                                        class="w-2/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                        Action Detail</th>
                                    <th
                                        class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                        Source IP</th>
                                    <th
                                        class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @forelse($activities as $log)
                                    <tr>
                                        <td class="px-4 py-2">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $log->action->value)) }}
                                        </td>
                                        <td class="px-4 py-2">{{ $log->ip_address ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">
                                            @php
                                                $level = strtoupper($log->level->value ?? 'info');
                                                $badgeClass = match ($level) {
                                                    'CRITICAL' => 'kt-badge-destructive',
                                                    'WARNING' => 'kt-badge-warning',
                                                    default => 'kt-badge-primary',
                                                };
                                            @endphp
                                            <span
                                                class="kt-badge kt-badge-outline {{ $badgeClass }}">{{ $level }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-muted-foreground">No recent
                                            activity found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>



    </div>

    <!-- End Main Grid Container -->
@endsection
