@extends('layouts.main.base') <!-- or your main layout -->
@dd($data)
@section('content')
    <div id="app" class="container-wrapper">

        <!-- HEADER / USER CONTEXT BAR -->
        <header class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold text-gray-800">
                    <span id="user-fullname">Jane Doe</span>
                    <span class="ml-2 text-lg font-normal text-gray-400">#USR-0042</span>
                </h1>
                <span id="user-role-badge"
                    class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase text-indigo-700"></span>
            </div>
            <div class="mt-4 flex items-center justify-end gap-2">
                <button type="button" class="kt-btn kt-btn-success kt-btn-sm rounded-md px-4 py-1.5">
                    <i class="ki-filled ki-lock mr-1"></i> Suspend
                </button>
                <button type="button" class="kt-btn kt-btn-mono kt-btn-sm rounded-md px-4 py-1.5">
                    <i class="ki-filled ki-trash mr-1"></i> Disable
                </button>
                <button type="button" class="kt-btn kt-btn-destructive kt-btn-sm rounded-md px-4 py-1.5">
                    <i class="ki-filled ki-security-user mr-1"></i> Block
                </button>
            </div>
        </header>

        <!-- 🧭 METRONIC TABS -->
        <div class="space-y-3">
            <div class="kt-tabs kt-tabs-line" data-kt-tabs="true">
                <button class="kt-tab-toggle active" data-kt-tab-toggle="#tab-profile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-badge-check" aria-hidden="true">
                        <path
                            d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z">
                        </path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>Profile Information</button><button class="kt-tab-toggle" data-kt-tab-toggle="#tab-access">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-square-user" aria-hidden="true">
                        <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                        <circle cx="12" cy="10" r="3"></circle>
                        <path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"></path>
                    </svg>Access Matrix
                </button><button class="kt-tab-toggle" data-kt-tab-toggle="#tab-security">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-calendar" aria-hidden="true">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>Account Security
                </button>
                <button class="kt-tab-toggle" data-kt-tab-toggle="#tab-audit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-calendar" aria-hidden="true">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>Audit
                </button>
            </div>

            <!-- TAB PANES -->
            <div class="text-sm" id="tab-content">

                <!-- 1. PROFILE -->
                <div id="tab-profile">
                    @include('pages.user-management.partials.basic_details')
                </div>

                <!-- 2. ACCESS -->
                <div id="tab-access" class="hidden">
                    @include('pages.user-management.partials.access-matrix')
                </div>

                <!-- 3. SECURITY -->
                <div id="tab-security" class="hidden">
                    @include('pages.user-management.partials.security-settings')
                </div>

                <!-- 4. AUDIT -->
                <div id="tab-audit" class="hidden">
                    @include('pages.user-management.partials.user-audit')
                </div>
            </div>
        </div>

        <!-- FORM ACTIONS -->
        <div class="sticky bottom-0 z-10">
            <div class="mt-4 flex items-center justify-end gap-2">
                <button type="button" class="kt-btn kt-btn-success kt-btn-sm rounded-md px-4 py-1.5">
                    <i class="fas kt-plus mr-1"></i> Save Changes
                </button>
                <button type="button" class="kt-btn kt-btn-destructive kt-btn-sm rounded-md px-4 py-1.5">
                    <i class="fas kt-ban mr-1"></i> Discard Changes
                </button>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // --- ACTUAL DATA INJECTED FROM LARAVEL ---
        // Using a reliable fallback ({}) for objects and ([]) for arrays
        // to prevent Blade's PHP compiler from encountering null/undefined and misinterpreting the comma.

        // Use (object) [] or just {} for the main user object to ensure an empty JSON object if $profileUser is null
        const ACTUAL_USER = @json($profileUser ?? (object) []);
        const PERMISSIONS_MATRIX = @json($permissions ?? []);
        const AUDIT_LOG = @json($activities ?? []);
        const ROLE_NAMES = @json($roles ?? []);

        // 🚀 NEW DATA INJECTION: Fetching sessions via the relationship on $profileUser
        // DEV NOTE: You must ensure $profileUser->sessions is loaded (e.g., $user->load('sessions')) or it will fail.
        const SESSIONS_LIST = @json($activeSessions ?? []);
        const REVOKE_SESSION_URL_TEMPLATE =
            '{{ route('admin.user_management.sessions.revoke', ['user' => ':userId', 'sessionId' => ':sessionId']) }}';

        //const DEFAULT_AVATAR_URL = 'https://placehold.co/150x150/cbd5e1/4f46e5?text=DFLT';

        // --- UTILITY FUNCTIONS ---

        function updateLockStatusUI(isLocked) {
            const btn = document.getElementById('toggle-lock-btn');
            const icon = document.getElementById('lock-icon-header');
            const text = document.getElementById('lock-text');

            // Reset classes
            btn.classList.remove(
                'text-red-600',
                'border-red-400',
                'hover:bg-red-50',
                'text-green-600',
                'border-green-400',
                'hover:bg-green-50'
            );

            if (isLocked) {
                btn.classList.add('text-red-600', 'border-red-400', 'hover:bg-red-50');
                icon.classList.remove('fa-unlock-alt');
                icon.classList.add('fa-lock');
                text.textContent = 'Unlock Account';
            } else {
                btn.classList.add('text-green-600', 'border-green-400', 'hover:bg-green-50');
                icon.classList.remove('fa-lock');
                icon.classList.add('fa-unlock-alt');
                text.textContent = 'Lock Account';
            }
        }

        function renderUserData() {
            const user = ACTUAL_USER;
            const roleNames = ROLE_NAMES;

            // Profile Summary
            document.getElementById(
                'user-fullname'
            ).textContent = `${user.first_name || 'N/A'} ${user.lastname || 'N/A'}`;

            //document.getElementById('user-avatar').src = user.avatar_url || DEFAULT_AVATAR_URL;

            document.getElementById('user-role-badge').textContent = roleNames.length > 0 ? roleNames[0] : 'No Role';

            // Form Fields
            document.getElementById('first_name').value = user.name || '';
            document.getElementById('last_name').value = user.lastname || '';
            document.getElementById('email').value = user.email || '';
            // document.getElementById('job_title').value = user.job_title || '';

            // Lock Toggle (Assuming 'is_locked' field exists on your User model)
            const isLocked = user.is_locked || false;
            updateLockStatusUI(isLocked);
            document.getElementById('toggle-lock-btn').addEventListener('click', (e) => {
                alert(
                    `Account status flipped to: ${
                !isLocked ? 'LOCKED' : 'ACTIVE'
                }. (Mock: This would trigger an API call.)`
                );
            });

            // Set Role Select to current role
            const roleSelect = document.getElementById('role-select');
            if (roleSelect && roleNames.length > 0) {
                Array.from(roleSelect.options).forEach((option) => {
                    // Check if the option value or text matches any of the user's role names
                    if (roleNames.includes(option.value) || roleNames.includes(option.textContent.trim())) {
                        option.selected = true;
                    }
                });
            }
        }

        /**
         * Renders the technical permission matrix data table.
         */
        function renderPermissionMatrix() {
            const container = document.getElementById('permission-matrix-body');
            container.innerHTML = '';

            PERMISSIONS_MATRIX.forEach((perm) => {
                // --- DATA MAPPING: Converting controller output to UI strings ---
                const isDenied = perm.forbidden;
                const status = isDenied ? 'Denied' : 'Allowed';

                let sourceDisplay = '';
                let note = 'N/A';
                let isActionable = false;

                switch (perm.source) {
                    case 'forbid':
                        sourceDisplay = 'Explicit Denial';
                        note = 'Security Override (Forbids Grant)';
                        isActionable = true;
                        break;
                    case 'temporary':
                        sourceDisplay = 'Temporary Grant';
                        note = 'Time-limited access';
                        isActionable = true;
                        break;
                    case 'direct':
                        sourceDisplay = 'Direct Grant';
                        note = 'Individual permission assigned';
                        isActionable = true;
                        break;
                    case 'role':
                    default:
                        sourceDisplay = `Role: ${perm.category || 'N/A'}`;
                        break;
                }

                // --- RENDERING LOGIC ---
                const statusBg = isDenied ?
                    'kt-badge kt-badge-outline kt-badge-destructive' :
                    'kt-badge kt-badge-outline kt-badge-success';
                const actionIcon = isDenied ? 'ki-filled ki-arrows-circle' : 'ki-filled ki-minus-circle';
                const actionTitle = isDenied ?
                    'Clear Explicit Denial' :
                    'Revoke Direct/Temporary Grant';

                const actionDisabled = !isActionable;

                const sourceColor = perm.source === 'role' ?
                    'text-indigo-600' :
                    perm.source === 'forbid' ?
                    'text-red-600' :
                    'text-slate-600';

                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50 transition duration-150';
                row.innerHTML = `
                <td class="px-4 text-xs font-medium text-slate-800"><i class="ki-outline ki-message-programming"></i> ${perm.name}</td>
                <td class="px-4 text-xs font-medium ${sourceColor}">${sourceDisplay}</td>
                <td class="px-4 text-xs font-medium text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusBg}">
                        ${status}
                    </span>
                </td>
                <td class="px-4 text-xs text-slate-500 italic">${note}</td>
                <td class="px-4 text-right">
                    <button type="button" title="${actionTitle}" ${actionDisabled ? 'disabled' : ''}
                        class="text-sm ${
                            actionDisabled
                                ? 'text-slate-300 cursor-not-allowed'
                                : 'text-slate-500 hover:text-indigo-600'
                        } p-1 transition">
                        <i class="${actionIcon}"></i>
                    </button>
                </td>
            `;
                container.appendChild(row);
            });
        }
        console.log('SESSIONS_LIST:', SESSIONS_LIST);
        /**
         * Renders the active sessions list into the container.
         */
        function renderActiveSessions() {
            // Ensure SESSIONS_LIST and ACTUAL_USER are defined globally or passed here
            if (typeof SESSIONS_LIST === 'undefined' || typeof ACTUAL_USER === 'undefined') {
                console.error("SESSIONS_LIST or ACTUAL_USER is not defined.");
                return;
            }

            const sessions = SESSIONS_LIST;
            const container = document.getElementById('active-sessions-list');
            if (!container) return; // Exit if container isn't found

            container.innerHTML = '';

            if (sessions.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 italic">No active sessions found.</p>';
                return;
            }

            sessions.forEach((session) => {
                const isCurrent = session.current || false;
                // Add custom logic for session status display here (if needed)

                // 🚀 PATCH: Generate the specific URL for this session using the template
                const revokeUrl = REVOKE_SESSION_URL_TEMPLATE
                    .replace(':userId', ACTUAL_USER.public_id)
                    .replace(':sessionId', session.id);

                const sessionDiv = document.createElement('div');
                sessionDiv.className =
                    'flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 border-b last:border-b-0';

                // Build the HTML content for the session item
                sessionDiv.innerHTML = `
                <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                    <div>
                        <p class="text-sm font-semibold">${session.device}</p>
                        <p class="text-xs text-gray-500">${session.ip_address || 'N/A'} - ${session.location || 'Unknown Location'}</p>
                        <p class="text-xs text-gray-500 italic">Last Active: ${session.last_active_at || 'Just Now'}</p>
                    </div>
                </div>
                ${
                    !isCurrent
                    ? `<button type="button"
                                                            class="revoke-session-btn px-3 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-md hover:bg-red-50 transition duration-150"
                                                            data-revoke-url="${revokeUrl}">
                                                            <i class="fas fa-sign-out-alt mr-1"></i> Revoke
                                                        </button>`
                    : `<span class="text-xs text-blue-500 italic mt-2 sm:mt-0 font-medium">Current Session</span>`
                }
            `;
                container.appendChild(sessionDiv);
            });

            attachSessionRevokeListeners();
        }

        /**
         * Attaches AJAX listeners to the 'Revoke' buttons.
         */
        function attachSessionRevokeListeners() {
            document.querySelectorAll('.revoke-session-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (!confirm(
                            'Are you sure you want to revoke this session? The user will be logged out on that device.'
                        )) {
                        return;
                    }

                    // 🚀 PATCH: Retrieve the correctly generated URL from the data attribute
                    const url = this.dataset.revokeUrl;

                    // 🚨 Security Check: Ensure CSRF token is available
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

                    if (!csrfMeta) {
                        alert('Security Error: CSRF token not found. Cannot proceed with revocation.');
                        console.error('CSRF meta tag is missing from the HTML document.');
                        return;
                    }
                    const csrfToken = csrfMeta.content;

                    // Disable button and show loading state while processing
                    const originalText = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Revoking...';


                    // 🚀 PATCH: Use the retrieved, correctly routed URL in the fetch call
                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            // Try to parse JSON, even on errors
                            return response.json().catch(() => ({
                                message: 'No response body.',
                                deleted_count: 0
                            })).then(data => ({
                                status: response.status,
                                data
                            }));
                        })
                        .then(({
                            status,
                            data
                        }) => {
                            if (status === 200) {
                                alert('Session successfully revoked!');
                                // Remove the session item from the UI
                                this.closest('.flex.flex-col').remove();
                            } else if (status === 404) {
                                alert(
                                    `Failed: Session not found or already terminated. Status: ${status}.`
                                );
                                this.closest('.flex.flex-col')
                                    .remove(); // Assume it's gone and clean up
                            } else {
                                alert(
                                    `Failed to revoke session. Status: ${status}. Message: ${data.message || 'Server Error.'}`
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Revocation error:', error);
                            alert('An unexpected error occurred during revocation.');
                        })
                        .finally(() => {
                            // Re-enable button on failure
                            if (this.closest('body')) {
                                this.disabled = false;
                                this.innerHTML = originalText;
                            }
                        });
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // ... (call to renderActiveSessions) ...

            const revokeAllButton = document.getElementById('revoke-all-sessions-btn');
            if (revokeAllButton) {
                revokeAllButton.addEventListener('click', function() {
                    if (!confirm(
                            '🚨 WARNING: Are you sure you want to revoke ALL active sessions for this user? They will be logged out on all devices.'
                        )) {
                        return;
                    }

                    const url = this.getAttribute('data-revoke-all-url');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    // Disable button and show loading state
                    const originalText = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json().catch(() => {}).then(data => ({
                            status: response.status,
                            data
                        })))
                        .then(({
                            status,
                            data
                        }) => {
                            if (status === 200) {
                                alert(`Success: ${data.message}`);
                                // Optionally refresh the sessions list in the UI
                                renderActiveSessions();
                            } else {
                                alert(
                                    `Failed to revoke sessions. Status: ${status}. Message: ${data.message || 'Server Error.'}`
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Revocation error:', error);
                            alert('An unexpected error occurred.');
                        })
                        .finally(() => {
                            this.disabled = false;
                            this.innerHTML = originalText;
                        });
                });
            }
        });

        /**
         * Renders the audit log data table using the SystemActivityLog data.
         */
        function renderAuditLog() {
            const tbody = document.getElementById('audit-log-body');
            tbody.innerHTML = '';

            AUDIT_LOG.forEach((log) => {
                let statusClass, statusIcon;

                // Check for the Enum value (log.level.value) or assume it's a string (log.level)
                const level = (log.level && log.level.value ? log.level.value : log.level || 'info').toLowerCase();

                switch (level) {
                    case 'success':
                    case 'info':
                    case 'notice':
                        statusClass = 'kt-badge kt-badge-outline kt-badge-success';
                        statusIcon = 'ki-filled ki-check-circle';
                        break;
                    case 'warning':
                        statusClass = 'kt-badge kt-badge-outline kt-badge-warning';
                        statusIcon = 'ki-filled ki-information';
                        break;
                    case 'error':
                    case 'critical':
                    case 'denied':
                        statusClass = 'kt-badge kt-badge-outline kt-badge-destructive';
                        statusIcon = 'ki-filled ki-time';
                        break;
                    default:
                        statusClass = 'kt-badge kt-badge-outline kt-badge-info';
                        statusIcon = 'ki-filled ki-information-1';
                }

                // created_at is a string like "2024-10-14T14:05:12.000000Z" from Laravel JSON
                const dateString = log.created_at || '';
                const datePart = dateString.substring(0, 10);
                const timePart = dateString.substring(11, 16);

                const actionText = log.message || log.action;

                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50 transition duration-100';
                row.innerHTML = `
                <td class="px-4 whitespace-nowrap text-xs text-slate-500 font-mono">${datePart} <span class="font-normal text-slate-400">${timePart}</span></td>
                <td class="px-4 text-slate-800">${actionText}</td>
                <td class="px-4 whitespace-nowrap text-slate-600 font-mono text-xs">${log.ip_address || 'N/A'}</td>
                <td class="px-4 whitespace-nowrap">
                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        <i class="${statusIcon} mr-1"></i> ${level.toUpperCase()}
                    </span>
                </td>
            `;
                tbody.appendChild(row);
            });
        }
        console.log('Sessions:', SESSIONS_LIST);
        // --- INITIALIZATION ---
        window.onload = () => {
            //renderUserData(); // Assuming this is uncommented elsewhere
            renderPermissionMatrix();
            renderActiveSessions();
            renderAuditLog();
        };
    </script>
@endpush
