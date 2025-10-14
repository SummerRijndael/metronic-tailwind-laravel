@extends('layouts.main.base') <!-- or your main layout -->

@section('content')
    <x-modal id="welcome_modal" title="Welcome to Metronic" :autoShow="false" :image="'assets/media/illustrations/21.svg'">
        Hello {{ auth()->user()->name }}! we're thrilled to have you on board and excited for the journey ahead
        together.

        <x-slot name="actions">
            <a href="{{ url('/dashboard') }}" class="kt-btn kt-btn-primary flex justify-center">
                Show me around
            </a>
            <a href="#" class="kt-btn kt-btn-outline ms-2 flex justify-center">
                Skip
            </a>

        </x-slot>
    </x-modal>

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
            <div class="flex items-center space-x-3">
                <button type="button" id="toggle-lock-btn" class="kt-btn kt-btn-outline kt-btn-sm transition duration-150">
                    <i class="fas fa-lock mr-2" id="lock-icon-header"></i>
                    <span id="lock-text">Lock Account</span>
                </button>
                <button type="button"
                    class="kt-btn kt-btn-sm border border-red-400 text-red-600 shadow-sm transition duration-150 hover:bg-red-600 hover:text-white">
                    <i class="fas fa-trash-alt mr-1"></i> Delete User
                </button>
            </div>
        </header>

        <!-- 🧭 METRONIC TABS -->
        <div class="space-y-3">
            <div class="kt-tabs kt-tabs-line" data-kt-tabs="true">
                <button class="kt-tab-toggle active" data-kt-tab-toggle="#tab-profile"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-badge-check" aria-hidden="true">
                        <path
                            d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z">
                        </path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                    <i class="fas fa-id-card-alt mr-2"></i> Profile Details
                </button>
                <button class="kt-tab-toggle" data-kt-tab-toggle="#tab-access">
                    <i class="fas fa-user-shield mr-2"></i> Access Matrix (RBAC)
                </button>
                <button class="kt-tab-toggle" data-kt-tab-toggle="#tab-security">
                    <i class="fas fa-shield-alt mr-2"></i> Security & Sessions
                </button>
                <button class="kt-tab-toggle" data-kt-tab-toggle="#tab-audit">
                    <i class="fas fa-history mr-2"></i> Audit Trail
                </button>
            </div>

            <!-- TAB PANES -->
            <div class="text-sm" id="tab-content">

                <!-- 1. PROFILE -->
                <div id="tab-profile">
                    <div class="kt-card tab-pane" id="tab-profile">
                        <div class="kt-card shadow-md">
                            <div class="kt-card-content">
                                <h2 class="mb-6 pb-3 text-lg font-semibold text-gray-700">Basic Information</h2>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <div class="flex flex-col items-center md:col-span-1">
                                        {{-- Image Input Component --}}
                                        <x-image-uploader name="avatar" id="user-avatar" align="left" size="lg"
                                            :preview="Auth::user()->avatar != 'blank.png'
                                                ? asset('storage/' . Auth::user()->avatar)
                                                : asset('assets/media/avatars/blank.png')">
                                            <span class="mt-1 text-[8px] font-normal text-gray-400">PNG, JPG</span>
                                        </x-image-uploader>
                                        <button type="button" id="restore-avatar-btn"
                                            class="rounded-md p-1 text-xs font-medium text-gray-500 transition duration-150 hover:bg-gray-50 hover:text-indigo-600">
                                            <i class="fas fa-redo-alt mr-1"></i> Reset Avatar
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:col-span-2">
                                        <div>
                                            <label for="first_name"
                                                class="mb-1 block text-xs font-medium uppercase text-gray-500">First
                                                Name</label>
                                            <!-- Fix applied here: Added kt-input class -->
                                            <input type="text" name="first_name" id="first_name" required
                                                class="kt-input w-full text-sm shadow-sm">
                                        </div>
                                        <div>
                                            <label for="last_name"
                                                class="mb-1 block text-xs font-medium uppercase text-gray-500">Last
                                                Name</label>
                                            <!-- Fix applied here: Added kt-input class -->
                                            <input type="text" name="last_name" id="last_name" required
                                                class="kt-input w-full text-sm shadow-sm">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="email"
                                                class="mb-1 block text-xs font-medium uppercase text-gray-500">Primary Email
                                                Address</label>
                                            <!-- Fix applied here: Added kt-input class -->
                                            <input type="email" name="email" id="email" required
                                                class="kt-input w-full text-sm shadow-sm">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="job_title"
                                                class="mb-1 block text-xs font-medium uppercase text-gray-500">Job
                                                Title</label>
                                            <!-- Fix applied here: Added kt-input class -->
                                            <input type="text" name="job_title" id="job_title"
                                                placeholder="e.g., Senior Analyst"
                                                class="kt-input w-full text-sm shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. ACCESS -->
                <div id="tab-access" class="hidden">
                    <div class="kt-card shadow-md">
                        <div class="kt-card-content">
                            <h2 class="mb-6 pb-3 text-lg font-semibold text-slate-700">
                                Permission Matrix & Role Assignment
                            </h2>

                            <!-- Role Quick Change -->
                            <div class="mb-6 flex items-center justify-between rounded-md border border-border p-3">
                                <label for="role-select" class="flex items-center text-sm font-medium text-indigo-700">
                                    <i class="fas fa-tag mr-2 text-indigo-500"></i> Primary Role Assignment:
                                </label>
                                <select class="kt-select kt-select-md" id="role-select" data-kt-select="true"
                                    data-kt-select-multiple="true" data-kt-select-placeholder="Select a framework"
                                    data-kt-select-config='{
          "optionsClass": "kt-scrollable overflow-auto max-h-[250px]"
        }'>
                                    <option value="admin">Super Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="analyst">Analyst (Current)</option>
                                    <option value="guest">Guest</option>
                                </select>
                            </div>

                            <!-- Permission Table -->
                            <div
                                class="custom-scrollbar max-h-[500px] overflow-hidden overflow-y-auto rounded-md border border-border">
                                <table class="kt-table dense-table min-w-full divide-y divide-slate-200">
                                    <thead class="sticky top-0 bg-slate-50">
                                        <tr>
                                            <th
                                                class="w-1/4 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Permission Key
                                            </th>
                                            <th
                                                class="w-1/4 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Source
                                            </th>
                                            <th
                                                class="w-1/6 px-4 py-3 text-left text-center text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Effective Status
                                            </th>
                                            <th
                                                class="w-1/4 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Notes / Override
                                            </th>
                                            <th
                                                class="w-1/12 px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="permission-matrix-body" class="divide-y divide-slate-100 bg-white text-sm">
                                        <!-- Populated by JS -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Direct Assignment Controls -->
                            <div class="mt-4 flex items-center justify-end gap-3">
                                <select id="direct-perm-select" class="kt-select" data-kt-select="true"
                                    data-kt-select-multiple="true" data-kt-select-placeholder="Select a framework"
                                    data-kt-select-config='{
          "optionsClass": "kt-scrollable overflow-auto max-h-[250px]"
        }'>
                                    <option value="">Select permission to manage...</option>
                                    <option value="create_users">create_users</option>
                                    <option value="delete_audit_logs">delete_audit_logs</option>
                                    <option value="override_budget">override_budget</option>
                                </select>
                                <button type="button" class="kt-btn kt-btn-success rounded-md px-4 py-1.5">
                                    <i class="fas fa-plus mr-1"></i> Direct Grant
                                </button>
                                <button type="button" class="kt-btn kt-btn-destructive rounded-md px-4 py-1.5">
                                    <i class="fas fa-ban mr-1"></i> Explicit Deny
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. SECURITY -->
                <div id="tab-security" class="hidden">
                    <div class="kt-card shadow-md">
                        <div class="kt-card-content">
                            <h2 class="mb-6 pb-3 text-lg font-semibold text-slate-700">
                                Authentication & Session Management
                            </h2>

                            <!-- Policy & Credential Health -->
                            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div
                                    class="flex items-center justify-between rounded-md border border-border bg-slate-50 p-4">
                                    <div>
                                        <p class="font-medium text-slate-700">Password Health</p>
                                        <p class="text-sm text-slate-500" id="last-password-change">
                                            Changed 12 days ago
                                        </p>
                                    </div>
                                    <button type="button"
                                        class="rounded-md border border-border px-4 py-2 text-sm font-medium text-red-600 transition duration-150 hover:bg-red-50">
                                        Force Reset
                                    </button>
                                </div>
                                <div class="flex items-center justify-between rounded-md border border-border p-4">
                                    <div>
                                        <p class="font-medium text-slate-700">Multi-Factor Authentication (MFA)</p>
                                        <p class="text-sm font-medium text-green-600" id="mfa-status">
                                            Enabled (TOTP App)
                                        </p>
                                    </div>
                                    <button type="button"
                                        class="rounded-md border border-border px-4 py-2 text-sm font-medium text-indigo-600 transition duration-150 hover:bg-indigo-50">
                                        View Setup
                                    </button>
                                </div>
                            </div>

                            <!-- Active Sessions List -->
                            <h3 class="mb-4 flex items-center justify-between pb-3 text-base font-semibold text-slate-700">
                                Active Sessions
                                <button type="button"
                                    class="rounded-md border border-border px-3 py-1.5 text-xs font-medium text-red-600 transition duration-150 hover:bg-red-50"
                                    onclick="alert('All sessions revoked.')">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Revoke All
                                </button>
                            </h3>
                            <div id="active-sessions-list" class="space-y-3">
                                <!-- Sessions populated by JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. AUDIT -->
                <div id="tab-audit" class="hidden">
                    <div class="kt-card shadow-md">
                        <div class="kt-card-content">
                            <h2 class="mb-6 pb-3 text-lg font-semibold text-slate-700">
                                User Activity Log
                            </h2>

                            <!-- Filtering Controls -->
                            <div class="mb-4 flex flex-col gap-3 sm:flex-row">
                                <input type="text" placeholder="Search event, resource, or IP address..."
                                    class="kt-input flex-grow rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" />
                                <select
                                    class="kt-select w-full rounded-md border border-slate-300 px-3 py-2 text-sm sm:w-40">
                                    <option>All Statuses</option>
                                    <option>Success</option>
                                    <option>Denied</option>
                                    <option>Warning</option>
                                </select>
                                <button type="button"
                                    class="kb-btn kt-btn-outline rounded-md px-4 py-2 text-sm font-medium text-slate-700 transition duration-150 hover:bg-slate-50">
                                    Export CSV <i class="fas fa-download ml-1"></i>
                                </button>
                            </div>

                            <!-- Audit Log Table -->
                            <div
                                class="custom-scrollbar max-h-[500px] overflow-hidden overflow-y-auto rounded-md border border-border">
                                <table class="kt-table dense-table min-w-full divide-y divide-slate-200">
                                    <thead class="sticky top-0 bg-slate-50">
                                        <tr>
                                            <th
                                                class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Timestamp
                                            </th>
                                            <th
                                                class="w-2/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Action Detail
                                            </th>
                                            <th
                                                class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Source IP
                                            </th>
                                            <th
                                                class="w-1/5 px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="audit-log-body" class="divide-y divide-slate-100 bg-white text-xs">
                                        <!-- Log entries populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM ACTIONS -->
        <div class="sticky bottom-0 z-10 -mx-8 mt-6 flex justify-end border-t border-border bg-white px-8 py-4 shadow-md">
            <button type="button" onclick="console.log('Discarding changes...');"
                class="kt-btn kt-btn-outline kt-btn-sm mr-3 shadow-sm">
                <i class="fas fa-undo-alt mr-2"></i> Discard Changes
            </button>
            <button type="submit"
                class="kt-btn kt-btn-sm bg-emerald-500 font-bold text-white shadow-lg shadow-emerald-500/30 transition duration-150 hover:bg-emerald-600">
                <i class="fas fa-save mr-2"></i> Apply & Save
            </button>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // --- MOCK DATA ---
        const MOCK_USER = {
            id: 42,
            first_name: 'Jane',
            last_name: 'Doe',
            email: 'jane.doe@example.com',
            job_title: 'Senior Project Manager',
            is_locked: false,
            role_name: 'Analyst',
            avatar_url: 'https://placehold.co/150x150/10b981/ffffff?text=JD',
        };

        const DEFAULT_AVATAR_URL = 'https://placehold.co/150x150/cbd5e1/4f46e5?text=DFLT';

        // Combined permission list for the matrix
        const MOCK_PERMISSIONS_MATRIX = [{
                name: 'view_reports',
                source: 'Role: Analyst',
                status: 'Allowed',
                note: ''
            },
            {
                name: 'manage_users',
                source: 'Role: Analyst',
                status: 'Denied',
                note: 'Role policy restriction',
            },
            {
                name: 'view_financial_data',
                source: 'Direct Grant',
                status: 'Allowed',
                note: 'Temporary access',
            },
            {
                name: 'delete_critical_records',
                source: 'Direct Denial',
                status: 'Denied',
                note: 'Explicit security override',
            },
            {
                name: 'impersonate_user',
                source: 'Role: Analyst',
                status: 'Denied',
                note: ''
            },
            {
                name: 'export_data_full',
                source: 'Role: Analyst',
                status: 'Allowed',
                note: ''
            },
            {
                name: 'change_role',
                source: 'Role: Analyst',
                status: 'Denied',
                note: ''
            },
        ];

        const MOCK_SESSIONS = [{
                id: 1,
                device: 'Chrome 128 on Mac OS',
                ip: '203.0.113.45',
                location: 'New York, USA',
                last_activity: '1 minute ago',
                current: true,
            },
            {
                id: 2,
                device: 'Mobile Safari on iPhone',
                ip: '192.0.2.10',
                location: 'London, UK',
                last_activity: '3 hours ago',
                current: false,
            },
        ];

        const MOCK_AUDIT_LOG = [{
                timestamp: '2024-10-14 14:05:12',
                action: 'Login Successful (MFA required)',
                ip: '203.0.113.45',
                status: 'Success',
            },
            {
                timestamp: '2024-10-14 14:06:30',
                action: 'Updated Job Title field',
                ip: '203.0.113.45',
                status: 'Success',
            },
            {
                timestamp: '2024-10-14 14:07:01',
                action: 'Attempted to access /admin/users (Permission Denied)',
                ip: '203.0.113.45',
                status: 'Denied',
            },
            {
                timestamp: '2024-10-13 09:15:22',
                action: 'Session Timeout',
                ip: '192.0.2.10',
                status: 'Info',
            },
            {
                timestamp: '2024-10-12 21:40:55',
                action: 'Password reset requested via email',
                ip: '8.8.8.8',
                status: 'Warning',
            },
            {
                timestamp: '2024-10-11 10:00:00',
                action: 'Administrator (Admin-2) changed Primary Role to Analyst',
                ip: '10.1.1.1',
                status: 'Success',
            },
        ];

        // --- UTILITY FUNCTIONS ---

        function switchTab(tabName) {
            document.querySelectorAll('#main-tabs button').forEach((btn) => {
                btn.classList.remove('active-tab');
                if (btn.getAttribute('data-tab') === tabName) {
                    btn.classList.add('active-tab');
                }
            });
            document.querySelectorAll('.tab-pane').forEach((pane) => {
                pane.classList.add('hidden');
                if (pane.id === `tab-${tabName}`) {
                    pane.classList.remove('hidden');
                }
            });
        }

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
            // Profile Summary
            document.getElementById(
                'user-fullname'
            ).textContent = `${MOCK_USER.first_name} ${MOCK_USER.last_name}`;
            document.getElementById('user-avatar').src = MOCK_USER.avatar_url;
            document.getElementById('user-role-badge').textContent = MOCK_USER.role_name;

            // Form Fields
            document.getElementById('first_name').value = MOCK_USER.first_name;
            document.getElementById('last_name').value = MOCK_USER.last_name;
            document.getElementById('email').value = MOCK_USER.email;
            document.getElementById('job_title').value = MOCK_USER.job_title;

            // Lock Toggle
            updateLockStatusUI(MOCK_USER.is_locked);
            document.getElementById('toggle-lock-btn').addEventListener('click', (e) => {
                MOCK_USER.is_locked = !MOCK_USER.is_locked;
                updateLockStatusUI(MOCK_USER.is_locked);
                alert(
                    `Account status flipped to: ${
              MOCK_USER.is_locked ? 'LOCKED' : 'ACTIVE'
            }. This change would be applied upon saving.`
                );
            });

            // Set Role Select to current role
            const roleSelect = document.getElementById('role-select');
            Array.from(roleSelect.options).forEach((option) => {
                if (option.textContent.includes(MOCK_USER.role_name)) {
                    option.selected = true;
                }
            });
        }

        /**
         * Renders the technical permission matrix data table.
         */
        function renderPermissionMatrix() {
            const container = document.getElementById('permission-matrix-body');
            container.innerHTML = '';

            MOCK_PERMISSIONS_MATRIX.forEach((perm) => {
                const isDenied = perm.status === 'Denied';
                const statusBg = isDenied ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700';
                const actionIcon = isDenied ? 'fas fa-undo-alt' : 'fas fa-minus-circle';
                const actionTitle = isDenied ?
                    'Clear Explicit Denial' :
                    perm.source.startsWith('Direct') ?
                    'Revoke Direct Grant' :
                    'Cannot revoke role permission';
                const actionDisabled = !perm.source.startsWith('Direct');
                const sourceColor = perm.source.startsWith('Role') ?
                    'text-indigo-600' :
                    perm.source.startsWith('Direct Denial') ?
                    'text-red-600' :
                    'text-slate-600';

                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50 transition duration-150';
                row.innerHTML = `
                    <td class="px-4 text-xs font-medium text-slate-800">${perm.name}</td>
                    <td class="px-4 text-xs font-medium ${sourceColor}">${perm.source}</td>
                    <td class="px-4 text-xs font-medium text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusBg}">
                            ${perm.status}
                        </span>
                    </td>
                    <td class="px-4 text-xs text-slate-500 italic">${perm.note || 'N/A'}</td>
                    <td class="px-4 text-right">
                        <button type="button" title="${actionTitle}" ${
            actionDisabled ? 'disabled' : ''
          }
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

        /**
         * Renders the list of active user sessions.
         */
        function renderActiveSessions() {
            const container = document.getElementById('active-sessions-list');
            container.innerHTML = '';

            MOCK_SESSIONS.forEach((session) => {
                const isCurrent = session.current;
                const statusClass = isCurrent ?
                    'text-emerald-600 bg-emerald-50' :
                    'text-slate-500 bg-slate-50';

                const sessionDiv = document.createElement('div');
                sessionDiv.className =
                    'p-4 border border-border rounded-md flex flex-col sm:flex-row items-start sm:items-center justify-between transition-all duration-200 hover:shadow-sm';
                sessionDiv.innerHTML = `
                    <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                        <i class="fas fa-${
                          isCurrent ? 'laptop-code' : 'mobile-alt'
                        } text-2xl text-indigo-500"></i>
                        <div>
                            <p class="font-semibold text-slate-700 text-sm">${session.device}</p>
                            <p class="text-xs text-slate-500">${session.ip} &bull; ${
            session.location
          }</p>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full ${statusClass} mt-1 inline-block">
                                ${
                                  isCurrent
                                    ? 'Current Session (Active)'
                                    : `Last Active: ${session.last_activity}`
                                }
                            </span>
                        </div>
                    </div>
                    ${
                      !isCurrent
                        ? `
                                                                                                                                                                                                        <button type="button" onclick="alert('Revoking session ${session.id}')"
                                                                                                                                                                                                            class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-300 rounded-md hover:bg-red-50 transition duration-150">
                                                                                                                                                                                                            <i class="fas fa-sign-out-alt mr-1"></i> Revoke
                                                                                                                                                                                                        </button>`
                        : `<span class="text-xs text-slate-400 italic mt-2 sm:mt-0">Current Session</span>`
                    }
                `;
                container.appendChild(sessionDiv);
            });
        }

        /**
         * Renders the audit log data table.
         */
        function renderAuditLog() {
            const tbody = document.getElementById('audit-log-body');
            tbody.innerHTML = '';

            MOCK_AUDIT_LOG.forEach((log) => {
                let statusClass, statusIcon;
                switch (log.status) {
                    case 'Success':
                        statusClass = 'text-green-700 bg-green-100';
                        statusIcon = 'fas fa-check-circle';
                        break;
                    case 'Denied':
                        statusClass = 'text-red-700 bg-red-100';
                        statusIcon = 'fas fa-times-circle';
                        break;
                    case 'Warning':
                        statusClass = 'text-yellow-700 bg-yellow-100';
                        statusIcon = 'fas fa-exclamation-triangle';
                        break;
                    default: // Info
                        statusClass = 'text-slate-700 bg-slate-100';
                        statusIcon = 'fas fa-info-circle';
                }

                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50 transition duration-100';
                row.innerHTML = `
                    <td class="px-4 whitespace-nowrap text-xs text-slate-500 font-mono">${
                      log.timestamp.split(' ')[0]
                    } <span class="font-normal text-slate-400">${
            log.timestamp.split(' ')[1]
          }</span></td>
                    <td class="px-4 text-slate-800">${log.action}</td>
                    <td class="px-4 whitespace-nowrap text-slate-600 font-mono text-xs">${
                      log.ip
                    }</td>
                    <td class="px-4 whitespace-nowrap">
                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            <i class="${statusIcon} mr-1"></i> ${log.status}
                        </span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // --- INITIALIZATION ---
        window.onload = () => {
            renderUserData();
            renderPermissionMatrix();
            renderActiveSessions();
            renderAuditLog();

        };
    </script>
@endpush
