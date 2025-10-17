@extends('layouts.main.base')
@section('content')
    {{-- Main Container (Page Wrapper) --}}
    <div class="min-h-screen border border-border p-8 shadow-md">

        {{-- Content Area (Centered, Column Flex, with Gaps) --}}
        <div class="mx-auto flex max-w-screen-xl flex-col gap-6">

            {{-- ROW 1: Blocks 1 & 2 Grid (2 Columns on MD+) --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Block 1: Account Health Summary --}}
                <div>
                    <div class="kt-card border border-border shadow-md">
                        <div class="kt-card-head"></div>
                        <div class="kt-card-content">
                            <div class="p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-gray-800">Account Health Summary</h2>
                                    <div class="rounded-full bg-indigo-100 p-3 text-indigo-600">
                                        <i class="fas fa-chart-line text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                <div class="kt-badge-outline kt-badge-info rounded-lg bg-indigo-50 p-3">
                                    <p class="text-sm font-medium text-indigo-700">Total Users</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-indigo-900">{{ $totalUsers }}</p>
                                </div>
                                <div class="kt-badge-outline kt-badge-success rounded-lg bg-sky-50 p-3">
                                    <p class="text-sm font-medium text-sky-700">Logged In Now</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-sky-600">{{ $loggedInNow }}</p>
                                </div>
                                <div class="kt-badge-outline kt-badge-primary rounded-lg bg-emerald-50 p-3">
                                    <p class="text-sm font-medium text-emerald-700">Active</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-emerald-900">{{ $activeUsers }}</p>
                                </div>
                                <div class="kt-badge-outline kt-badge-destructive rounded-lg p-3">
                                    <p class="text-destructive text-sm font-medium">Blocked</p>
                                    <p class="text-destructive mt-0.5 text-2xl font-extrabold">{{ $blockedUsers }}</p>
                                </div>
                                <div class="kt-badge-outline kt-badge-warning rounded-lg p-3">
                                    <p class="text-sm font-medium text-yellow-700">Suspended</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-yellow-900">{{ $suspendedUsers }}</p>
                                </div>
                                <div class="rounded-lg bg-gray-100 p-3">
                                    <p class="text-sm font-medium text-gray-600">Disabled</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-gray-900">{{ $disabledUsers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Block 2: Permissions & Roles Overview --}}
                <div>
                    <div class="kt-card border border-border shadow-md">
                        <div class="kt-card-head"></div>
                        <div class="kt-card-content">
                            <div class="p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-gray-800">Permissions & Roles Overview</h2>
                                    <div class="rounded-full bg-purple-100 p-3 text-purple-600">
                                        <i class="fas fa-shield-alt text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                <div class="kt-badge-outline kt-badge-info rounded-lg bg-purple-50 p-3">
                                    <p class="text-sm font-medium text-purple-700">Administrators</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-purple-900">{{ $administrators }}</p>
                                </div>
                                <div class="kt-badge-outline kt-badge-warning rounded-lg bg-purple-50 p-3">
                                    <p class="text-sm font-medium text-purple-700">Editors</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-purple-900">{{ $editors }}</p>
                                </div>
                                <div class="kt-badge-outline kt-badge-primary rounded-lg bg-purple-50 p-3">
                                    <p class="text-sm font-medium text-purple-700">Standard Users</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-purple-900">{{ $standardUsers }}</p>
                                </div>
                                <div class="rounded-lg bg-blue-50 p-3">
                                    <p class="text-sm font-medium text-blue-700">via RBAC Role</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-blue-900">{{ $viaRbacRole }}</p>
                                </div>
                                <div class="rounded-lg bg-blue-50 p-3">
                                    <p class="text-sm font-medium text-blue-700">Directly Assigned</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-blue-900">{{ $directlyAssigned }}</p>
                                </div>
                                <div class="kt-badge-outline kt-badge-destructive rounded-lg bg-orange-50 p-3">
                                    <p class="text-sm font-medium text-orange-700">Perm. Overrides</p>
                                    <p class="mt-0.5 text-2xl font-extrabold text-orange-900">{{ $permOverrides }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- CLOSING TAG FOR ROW 1: Blocks 1 & 2 Grid --}}
            </div>

            {{-- ROW 2: Account Status Trend (Full Width) --}}
            {{-- This block should be independent of the first row to be full-width --}}
            <div class="kt-card border border-border p-6 shadow-md">
                <div class="kt-card-content flex min-h-[500px] flex-col">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">
                        Account Status Trend (Past 6 Months)
                    </h2>

                    <div class="grid flex-1 grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="flex flex-col">
                            <div id="userStatusTrendChart" class="flex-1"></div>
                        </div>

                        <div class="flex flex-col">
                            <div id="userStatusPieChart" class="flex-1"></div>
                        </div>
                    </div>

                    <p class="mt-4 text-xs italic text-gray-400">
                        The line and pie charts visualize the same simulated data (users: active, suspended, blocked).
                    </p>
                </div>
            </div>
            {{-- CLOSING TAG FOR ROW 2: Account Status Trend --}}

            <div class="grid grid-cols-1">

                <div class="kt-card">
                    <div class="kt-card-header flex-wrap gap-2">
                        <h3 class="kt-card-title text-sm">
                            User Accounts List
                        </h3>
                        <div id="tools" class="flex flex-wrap gap-2 lg:gap-5">
                            <div class="flex">
                                <label class="kt-input">
                                    <i class="ki-filled ki-magnifier">
                                    </i>
                                    <input data-kt-datatable-search="#kt_datatable_remote_source" placeholder="Search users"
                                        type="text" value="" />
                                </label>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                                <select class="kt-select w-36" data-kt-select="true"
                                    data-kt-select-placeholder="Select a status" id="status-filter">
                                    <option value="">
                                        All
                                    </option>
                                    <option value="active">
                                        Active
                                    </option>
                                    <option value="blocked">
                                        Blocked
                                    </option>
                                    <option value="suspended">
                                        Suspended
                                    </option>
                                    <option value="disabled">
                                        Disabled
                                    </option>
                                </select>
                                <x-date-picker id="data_range" placeholder="Filter by Date Range" value=""
                                    class="kt-input" :config="['mode' => 'range', 'showMonths' => 2]" />

                                <button id="filter" data-loading-button="true" type="submit"
                                    class="kt-btn kt-btn-outline">
                                    <i class="ki-filled ki-setting-4">
                                    </i>
                                    Filter
                                </button>

                            </div>
                        </div>
                    </div>
                    <div id="kt_datatable_remote_source" class="kt-card-table datatable-initialized"
                        data-kt-datatable-page-size="5" data-kt-datatable-state-save="true"
                        data-kt-datatable-initialized="true">
                        <div class="kt-table-wrapper kt-scrollable">
                            <table class="kt-table" data-kt-datatable-table="true">
                                <thead>
                                    <tr>

                                        <th scope="col" class="w-5" data-kt-datatable-column="check">
                                            <input type="checkbox" class="kt-checkbox" data-kt-datatable-check="true" />
                                        </th>
                                        <th scope="col" class="w-20" data-kt-datatable-column="full_name">
                                            <span class="kt-table-col"><span class="kt-table-col-label">Member</span><span
                                                    class="kt-table-col-sort"></span></span>
                                        </th>

                                        <th scope="col" class="w-24" data-kt-datatable-column="status">
                                            <span class="kt-table-col"><span class="kt-table-col-label">Status
                                                </span><span class="kt-table-col-sort"></span></span>
                                        </th>
                                        <th scope="col" class="w-24" data-kt-datatable-column="role">
                                            <span class="kt-table-col"><span class="kt-table-col-label">Role
                                                </span><span class="kt-table-col-sort"></span></span>
                                        </th>
                                        <th scope="col" class="w-24" data-kt-datatable-column="twfa_stat">
                                            <span class="kt-table-col"><span class="kt-table-col-label">2fa status
                                                </span><span class="kt-table-col-sort"></span></span>
                                        </th>
                                        <th scope="col" class="w-24" data-kt-datatable-column="created_at">
                                            <span class="kt-table-col"><span class="kt-table-col-label">Created
                                                    date</span><span class="kt-table-col-sort"></span></span>
                                        </th>
                                        <th scope="col" class="w-24" data-kt-datatable-column="actions">
                                            <span class="kt-table-col-label">Actions</span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!--begin:pagination-->
                        <div class="kt-datatable-toolbar">
                            <div class="kt-datatable-length">
                                Show<select class="kt-select kt-select-sm w-16" name="perpage"
                                    data-kt-datatable-size="true">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                </select>per page
                            </div>
                            <div class="kt-datatable-info">
                                <span data-kt-datatable-info="true">1-15 of 15</span>
                                <div class="kt-datatable-pagination" data-kt-datatable-pagination="true">
                                    <button class="kt-datatable-pagination-button kt-datatable-pagination-prev disabled"
                                        disabled="">
                                        <svg class="size-3.5 shrink-0 rtl:rotate-180 rtl:transform" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.86501 16.7882V12.8481H21.1459C21.3724 12.8481 21.5897 12.7581 21.7498 12.5979C21.91 12.4378 22 12.2205 22 11.994C22 11.7675 21.91 11.5503 21.7498 11.3901C21.5897 11.2299 21.3724 11.1399 21.1459 11.1399H8.86501V7.2112C8.86628 7.10375 8.83517 6.9984 8.77573 6.90887C8.7163 6.81934 8.63129 6.74978 8.53177 6.70923C8.43225 6.66869 8.32283 6.65904 8.21775 6.68155C8.11267 6.70405 8.0168 6.75766 7.94262 6.83541L2.15981 11.6182C2.1092 11.668 2.06901 11.7274 2.04157 11.7929C2.01413 11.8584 2 11.9287 2 11.9997C2 12.0707 2.01413 12.141 2.04157 12.2065C2.06901 12.272 2.1092 12.3314 2.15981 12.3812L7.94262 17.164C8.0168 17.2417 8.11267 17.2953 8.21775 17.3178C8.32283 17.3403 8.43225 17.3307 8.53177 17.2902C8.63129 17.2496 8.7163 17.18 8.77573 17.0905C8.83517 17.001 8.86628 16.8956 8.86501 16.7882Z"
                                                fill="currentColor"></path>
                                        </svg></button><button class="kt-datatable-pagination-button active disabled"
                                        disabled="">
                                        1</button><button
                                        class="kt-datatable-pagination-button kt-datatable-pagination-next disabled"
                                        disabled="">
                                        <svg class="size-3.5 shrink-0 rtl:rotate-180 rtl:transform" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.135 7.21144V11.1516H2.85407C2.62756 11.1516 2.41032 11.2415 2.25015 11.4017C2.08998 11.5619 2 11.7791 2 12.0056C2 12.2321 2.08998 12.4494 2.25015 12.6096C2.41032 12.7697 2.62756 12.8597 2.85407 12.8597H15.135V16.7884C15.1337 16.8959 15.1648 17.0012 15.2243 17.0908C15.2837 17.1803 15.3687 17.2499 15.4682 17.2904C15.5677 17.3309 15.6772 17.3406 15.7822 17.3181C15.8873 17.2956 15.9832 17.242 16.0574 17.1642L21.8402 12.3814C21.8908 12.3316 21.931 12.2722 21.9584 12.2067C21.9859 12.1412 22 12.0709 22 11.9999C22 11.9289 21.9859 11.8586 21.9584 11.7931C21.931 11.7276 21.8908 11.6683 21.8402 11.6185L16.0574 6.83565C15.9832 6.75791 15.8873 6.70429 15.7822 6.68179C15.6772 6.65929 15.5677 6.66893 15.4682 6.70948C15.3687 6.75002 15.2837 6.81959 15.2243 6.90911C15.1648 6.99864 15.1337 7.10399 15.135 7.21144Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end:pagination-->
                    </div>
                </div>

            </div>

            {{-- CLOSING TAG FOR Content Area (mx-auto flex max-w-screen-xl flex-col gap-6) --}}
        </div>
        {{-- CLOSING TAG FOR Main Container (min-h-screen border border-border p-8 shadow-md) --}}
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // --- Shared data for both charts ---
            const months = ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'];

            const seriesData = {
                newUsers: [150, 180, 250, 190, 100, 70],
                suspended: [15, 25, 10, 35, 20, 45],
                blocked: [5, 7, 8, 10, 12, 15],
            };

            // --- LINE CHART CONFIG (Left) ---
            const lineOptions = {
                chart: {
                    type: 'line',
                    height: '100%',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                colors: ['#10b981', '#f59e0b', '#ef4444'], // green, yellow, red
                series: [{
                        name: 'New Users',
                        data: seriesData.newUsers
                    },
                    {
                        name: 'Suspended',
                        data: seriesData.suspended
                    },
                    {
                        name: 'Blocked',
                        data: seriesData.blocked
                    },
                ],
                xaxis: {
                    categories: months,
                    labels: {
                        style: {
                            colors: '#6b7280'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6b7280'
                        }
                    },
                    min: 0,
                    title: {
                        text: 'Number of Users',
                        style: {
                            color: '#6b7280'
                        }
                    },
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#374151'
                    }
                },
                grid: {
                    borderColor: '#e5e7eb',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: val => `${val} users`
                    }
                },
            };

            const lineChart = new ApexCharts(document.querySelector("#userStatusTrendChart"), lineOptions);
            lineChart.render();

            // --- PIE CHART CONFIG (Right) ---
            // Aggregate totals for same dataset
            const totalNewUsers = seriesData.newUsers.reduce((a, b) => a + b, 0);
            const totalSuspended = seriesData.suspended.reduce((a, b) => a + b, 0);
            const totalBlocked = seriesData.blocked.reduce((a, b) => a + b, 0);

            const pieOptions = {
                chart: {
                    type: 'pie',
                    height: '100%',
                },
                labels: ['New Users', 'Suspended', 'Blocked'],
                colors: ['#10b981', '#f59e0b', '#ef4444'],
                series: [totalNewUsers, totalSuspended, totalBlocked],
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#374151'
                    }
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: val => `${val} total users`
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: val => `${val.toFixed(1)}%`
                },
            };

            const pieChart = new ApexCharts(document.querySelector("#userStatusPieChart"), pieOptions);
            pieChart.render();
        });
    </script>

    <script>
        'use strict';

        // DEV NOTE: Debounce utility function is placed at the top level for global access.
        const debounce = (func, wait) => {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        };

        // ... Your Configuration Constants (ASSET_BASE_PATH, API_ENDPOINT, etc.) remain here ...

        var KTDatatableRemoteData = (function() {

            // --- Configuration Constants (Read-Only) ---
            const ASSET_BASE_PATH = '{{ asset('assets/media/avatars/') }}';
            const STORAGE_BASE_PATH = '{{ asset('storage/') }}';
            const API_ENDPOINT = '{{ route('admin.user_management.dashboard.list') }}';
            const SHOW_ROUTE_BASE = '{{ route('profile.show', '') }}';
            const DESTROY_ROUTE_BASE = '{{ route('profile.destroy', '') }}';
            const EDIT_ROUTE_BASE = '{{ route('profile.update', '') }}';

            // --- Private State ---
            var instance = null;

            // --- Utility Functions (getAvatarPath remains the same) ---
            const getAvatarPath = function(avatarPathFragment) {
                if (!avatarPathFragment || avatarPathFragment.endsWith('blank.png')) {
                    return ASSET_BASE_PATH + '/blank.png';
                }
                return STORAGE_BASE_PATH + '/' + avatarPathFragment;
            };


            // --- Core Logic ---
            var init = function() {
                if (instance) {
                    return instance;
                }

                const datatableEl = document.getElementById('kt_datatable_remote_source');

                if (!datatableEl) {
                    console.error('KTDatatable element with ID "kt_datatable_remote_source" not found.');
                    return null;
                }

                // 💡 CORRECTION 1: Find the filter button's container and the loading button
                const filterDiv = document.getElementById('tools');

                let loadingButton = null;
                if (filterDiv) {
                    // Search for the loading button component inside the 'tools' container
                    loadingButton = filterDiv.querySelector('[data-loading-button="true"]');
                    console.log('loading button found');

                } else {
                    console.log('loading button not found');
                }

                // 💡 CORRECTION 2: Define the loading state toggler based on the found button
                const toggleLoadingState = loadingButton && typeof loadingButton.toggleLoadingState ===
                    'function' ?
                    loadingButton.toggleLoadingState :
                    (isLoading) => {
                        console.log("Loading state: " + isLoading)
                    };

                // Initialize datatable with remote data source
                const datatable = new KTDataTable(datatableEl, {
                    // ... API Configuration ...
                    apiEndpoint: API_ENDPOINT,
                    requestMethod: 'GET',
                    requestHeaders: {
                        Accept: 'application/json',
                    },

                    // --- mapRequest: Sends local filters to the API ---
                    mapRequest: function(queryParams) {
                        // ... filter logic remains the same ...

                        const statusFilterEl = document.getElementById("status-filter");
                        const colSortEl = document.getElementById("data_range");

                        const statusFilter = statusFilterEl ? statusFilterEl.value : null;
                        const colSort = colSortEl ? colSortEl.value : null;

                        if (statusFilter) {
                            queryParams.set("status", statusFilter);
                        }
                        if (colSort) {
                            queryParams.set("col_sort", colSort);
                        }

                        // 💡 CORRECTION 3: TURN ON loading state when the request is sent
                        toggleLoadingState(true);

                        return queryParams;
                    },

                    // --- mapResponse: Normalizes API response format ---
                    mapResponse: function(response) {
                        // ... response normalization logic remains the same ...

                        // 💡 CORRECTION 4: TURN OFF loading state when the response is received
                        toggleLoadingState(false);

                        if (response && response.data) {
                            return {
                                data: response.data,
                                totalCount: response.totalCount || response.data.length,
                                page: response.page || 1,
                                pageSize: response.pageSize || 5,
                                totalPages: response.totalPages || Math.ceil(response.totalCount / (
                                    response.pageSize || 5)),
                            };
                        }
                        // ... rest of the mapResponse logic (array/empty fallback) ...

                        // Ensure a valid structure is returned even on error/empty:
                        return {
                            data: [],
                            totalCount: 0,
                            page: 1,
                            pageSize: 5,
                            totalPages: 1
                        };
                    },

                    // ... Column Definitions (remain the same) ...
                    columns: {
                        // Checkbox Column
                        id: {
                            field: 'id',
                            title: 'ID',
                            sortable: false,
                            // OPTIMIZATION: Use a cleaner template string, ensure checkbox is the first column for visual consistency.
                            render: (value, row) => `
                        <input
                            type="checkbox"
                            class="kt-checkbox"
                            data-kt-datatable-row-check="true"
                            value="${row.id}"
                        />
                    `,
                        },

                        // Full Name / Avatar Column
                        full_name: {
                            title: 'Fullname',
                            // DEV NOTE: Ensure your API response contains the 'full_name' field.
                            render: (value, row) => {
                                const userIdp = row.id;
                                const imagePath = getAvatarPath(row.avatar);
                                // OPTIMIZATION: Maintain compact, semantic HTML with Tailwind/KTD classes.
                                // Using template literals here is the correct approach.

                                const showUrl_p = `${SHOW_ROUTE_BASE}/${userIdp}`;

                                return `
                                    <div class="flex items-center gap-2.5">
                                        <img
                                            src="${imagePath}"
                                            alt="${row.full_name}'s avatar"
                                            class="rounded-full size-7 shrink-0 object-cover shadow-md"
                                            onerror="this.onerror=null;this.src='${ASSET_BASE_PATH + '/blank.png'}';"
                                        >

                                        <div class="flex flex-col">
                                            <a
                                            href="${showUrl_p}"
                                            class="text-sm font-medium text-gray-800 hover:text-primary mb-px"
                                            title="${row.full_name}"
                                            >
                                            ${row.full_name}
                                            </a>
                                            <a
                                            href="mailto:${row.email ?? '#'}"
                                            class="text-sm text-gray-500 font-normal hover:text-primary"
                                            >
                                            ${row.email ?? 'No email'}
                                            </a>
                                        </div>
                                    </div>
                        `;
                            },
                        },

                        status: {
                            title: 'status'
                        },
                        role_name: {
                            title: 'Role'
                        },
                        twfa_stat: {
                            title: '2FA Status'
                        },
                        created_at: {
                            title: 'Created'
                        },

                        // Actions Column
                        actions: {
                            title: 'Actions',
                            sortable: false,
                            field: 'actions',
                            render: (value, row) => {
                                const userId = row.id;
                                // OPTIMIZATION: The base URLs are already constants, now we just append the ID.
                                // DEV NOTE: You should ensure your Blade routes are defined as:
                                // route('profile.show', ['profile' => '__ID__']) where '__ID__' is the placeholder.
                                // The simple concatenation below assumes the route expects the ID at the end.
                                const showUrl = `${SHOW_ROUTE_BASE}/${userId}`;
                                const destroyUrl = `${DESTROY_ROUTE_BASE}/${userId}`;
                                const editUrl = `${EDIT_ROUTE_BASE}/${userId}`;

                                return `
                            <div class="d-flex justify-content-end flex-shrink-0">
                                <a href="${showUrl}" title="View User" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                    <i class="ki-duotone ki-eye fs-2"></i>
                                </a>

                                <a href="${editUrl}" title="Edit User" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1">
                                    <i class="ki-duotone ki-pencil fs-2"></i>
                                </a>

                                <a href="#" title="Delete User"
                                    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                                    data-kt-action="delete"
                                    data-kt-user-id="${userId}"
                                    data-kt-delete-url="${destroyUrl}">
                                    <i class="ki-duotone ki-trash fs-2"></i>
                                </a>
                            </div>
                        `;
                            },
                        },
                    },

                    // ... Core Configuration ...
                    pageSize: 5,
                    stateSave: true,
                    search: {
                        smart: false,
                        regex: false,
                        delay: 500,
                    },

                    // ... Callbacks ...
                    callbacks: {
                        afterDraw: function(datatable) {
                            // e.g., initDeleteHandlers();
                        },
                    },
                });

                instance = datatable;
                return datatable;
            };

            // Public API
            return {
                init: init,
                getInstance: () => instance,
            };
        })();

        // --- Initialization Block ---

        function safeInitialize() {
            const instance = KTDatatableRemoteData.init();
            if (instance) {
                window.datatableInstance = instance;
                console.log('KTDatatable initialized successfully.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            safeInitialize();

            const filterButton = document.getElementById('filter');

            // Define the core action to be debounced
            const reloadDatatable = () => {
                const instance = window.datatableInstance;

                if (instance && typeof instance.reload === 'function') {
                    // Your API call logic fires here, which internally calls mapRequest
                    // where the loading state is turned ON.
                    instance.reload();
                } else {
                    console.warn('KTDataTable instance not found. Cannot reload.');
                }
            };

            // Create the debounced version of the action with a 300ms delay
            const debouncedReload = debounce(reloadDatatable, 300);

            if (filterButton) {
                filterButton.addEventListener('click', debouncedReload);
            }
        });
    </script>
@endpush
