@extends('layouts.main.base') <!-- or your main layout -->

@section('content')
    <div class="grid w-full space-y-5">
        <div class="min-h-screen p-8">
            <div class="grid w-full space-y-5">
                <div class="kt-card">
                    <div class="kt-card-header flex-wrap gap-2">
                        <h3 class="kt-card-title text-sm">
                            User Accounts List
                        </h3>
                        <div class="flex flex-wrap gap-2 lg:gap-5">
                            <div class="flex">
                                <label class="kt-input">
                                    <i class="ki-filled ki-magnifier">
                                    </i>
                                    <input data-kt-datatable-search="#kt_datatable_remote_source" placeholder="Search users"
                                        type="text" value="" />
                                </label>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                                <select class="kt-select w-36" id="filter_by" data-kt-select="true"
                                    data-kt-select-placeholder="Select a status">
                                    <option value="1">
                                        Active
                                    </option>
                                    <option value="2">
                                        Disabled
                                    </option>
                                    <option value="2">
                                        Pending
                                    </option>
                                </select>
                                <select class="kt-select w-36" id="sort" data-kt-select="true"
                                    data-kt-select-placeholder="Select a sort">
                                    <option value="1">
                                        Latest
                                    </option>
                                    <option value="2">
                                        Older
                                    </option>
                                    <option value="3">
                                        Oldest
                                    </option>
                                </select>
                                <button id="filter" class="kt-btn kt-btn-outline kt-btn-primary">
                                    <i class="ki-filled ki-setting-4">
                                    </i>
                                    Filters
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
                                        <th scope="col" class="w-24" data-kt-datatable-column="email">
                                            <span class="kt-table-col asc"><span
                                                    class="kt-table-col-label">Email</span><span
                                                    class="kt-table-col-sort"></span></span>
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
                                            <span class="kt-table-col"><span class="kt-table-col-label">Create
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
        </div>
    @endsection

    @push('scripts')
        <script>
            'use strict';

            /**
             * Remote Data Source Example
             *
             * This example demonstrates how to initialize a KTDataTable with a remote API data source.
             */
            var KTDatatableRemoteData = (function() {
                // Track initialization state
                var isInitialized = false;
                var instance = null;

                // FIX: Use asset() for all paths, including the storage path.
                // This is the standard Laravel way to access files in the 'public' folder (including storage link).
                // Default image: assets/media/avatar/blank.png
                const ASSET_BASE_PATH = '{{ asset('assets/media/avatars/') }}';

                // Uploaded image: public/storage/avatars/encryptedname.png
                // We use asset('storage/avatars') and assume the API returns the filename + subfolder (if any).
                const STORAGE_BASE_PATH = '{{ asset('storage/') }}';

                // Main initialization function
                var init = function() {
                    // Prevent multiple initializations
                    if (isInitialized && instance) {
                        return instance;
                    }

                    // Get the datatable element
                    var datatableEl = document.getElementById('kt_datatable_remote_source');
                    if (!datatableEl) {
                        return null;
                    }

                    // Clean up any previous instances
                    if (datatableEl.hasAttribute('data-kt-datatable-initialized')) {
                        if (
                            typeof KTDataTable !== 'undefined' &&
                            typeof KTDataTable.getInstance === 'function'
                        ) {
                            var oldInstance = KTDataTable.getInstance(datatableEl);
                            if (oldInstance && typeof oldInstance.dispose === 'function') {
                                oldInstance.dispose();
                            }
                        }

                        datatableEl.removeAttribute('data-kt-datatable-initialized');
                        if (datatableEl.instance) {
                            delete datatableEl.instance;
                        }
                    }

                    // Initialize datatable with remote data source
                    var datatable = new KTDataTable(datatableEl, {
                        apiEndpoint: '{{ route('users.list') }}',
                        requestMethod: 'GET',
                        requestHeaders: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                        },

                        // 💡 UPDATED: Accept defaultParams from the library and merge everything
                        requestParams: function(defaultParams) {
                            // Read the dropdowns and input values dynamically
                            const sortUI = document.getElementById('sort')?.value || 'yea';
                            const filterByUI = document.getElementById('filter_by')?.value || 'eyy';
                            const extraParamUI = document.getElementById('extra_param')?.value ||
                                ''; // optional future-proof slot

                            // Merge the default parameters (page, pageSize, sort, query)
                            // with your custom UI filters and fixed parameters.
                            return Object.assign({}, defaultParams, {
                                // 1. Dynamic UI Filters
                                sort_ui: sortUI.trim(),
                                filter_by_ui: filterByUI.trim(),
                                extra_ui: extraParamUI.trim(),

                                // 2. Fixed Custom Parameters
                                param1: 'test',
                                param2: 'test2',
                            });
                        },

                        // Format the API response, ensuring pagination data is properly mapped
                        mapResponse: function(response) {
                            if (response && response.data) {
                                return {
                                    data: response.data,
                                    totalCount: response.totalCount,
                                    // Include pagination data from the API response
                                    page: response.page || 1,
                                    pageSize: response.pageSize || 5,
                                    totalPages: response.totalPages ||
                                        Math.ceil(response.totalCount / (response.pageSize || 5)),
                                };
                            } else if (Array.isArray(response)) {
                                return {
                                    data: response,
                                    totalCount: response.length,
                                    page: 1,
                                    pageSize: 5,
                                    totalPages: Math.ceil(response.length / 5),
                                };
                            } else {
                                return {
                                    data: [],
                                    totalCount: 0,
                                    page: 1,
                                    pageSize: 5,
                                    totalPages: 1,
                                };
                            }
                        },

                        // Custom templates for column rendering
                        columns: {

                            id: {
                                field: 'id',
                                title: 'ID',
                                sortable: false,
                                // The renderer injects the checkbox HTML
                                render: function(value, row) {
                                    // Use the row.id for the checkbox value
                                    return `
                        <input
                            type="checkbox"
                            class="kt-checkbox"
                            data-kt-datatable-row-check="true"
                            value="${row.id}"
                        />
                    `;
                                },
                            },

                            full_name: {
                                title: 'Fullname',

                                render: function(value, row) {
                                    const avatarPathFragment = row.avatar;
                                    let imagePath = '';

                                    // 1. Determine correct image path
                                    if (!avatarPathFragment || avatarPathFragment === 'blank.png') {
                                        imagePath = ASSET_BASE_PATH + '/blank.png';
                                    } else {
                                        imagePath = STORAGE_BASE_PATH + '/' + avatarPathFragment;
                                    }

                                    // 2. Avatar HTML — subtle balance tweaks:
                                    // - Use gap-x-2 to control spacing instead of hard mr-*
                                    // - Ensure avatar and text remain aligned and compact
                                    return `
                        <div class="flex items-center gap-x-4">
                            <div class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center bg-gray-200 shrink-0 shadow-sm">
                                <img
                                    src="${imagePath}"
                                    alt="${row.full_name}'s avatar"
                                    class="w-full h-full object-cover rounded-full"
                                >
                            </div>
                            <span class="text-sm font-medium text-gray-700 truncate max-w-[150px]" title="${row.full_name}">
                                ${row.full_name}
                            </span>
                        </div>
                    `;
                                },
                            },

                            // **********************************************
                            // END: full_name renderer
                            // **********************************************
                            email: {
                                title: 'Email'
                            },
                            role_name: {
                                title: 'Role'
                            },
                            twfa_stat: {
                                title: '2fa Status'
                            },
                            created_at: {
                                title: 'Created',
                            },
                            // ... inside the columns: { ... } object

                            actions: {
                                title: 'Actions',
                                sortable: false, // Actions columns are typically not sortable
                                field: 'actions', // Placeholder field name, the content comes from the renderer

                                // The render function receives the cell value and the entire row object
                                render: function(value, row) {
                                    const userId = row.id;

                                    // Define the base URLs using Blade's route helper.
                                    // This is highly efficient as it executes the route helper once per column render.
                                    const showUrl = `{{ route('profile.show') }}/${userId}`;
                                    const destroyUrl = `{{ route('profile.destroy') }}/${userId}`;
                                    const editUrl = `{{ route('profile.update') }}/${userId}`;

                                    return `
                        <div class="flex items-center gap-2.5">

                            <!-- View Link -->
                            <a href="${showUrl}" title="View User" class="text-secondary hover:text-primary">
                               <i class="ki-duotone ki-eye">
                                  <span class="path1"></span>
                                  <span class="path2"></span>
                                  <span class="path3"></span>
                               </i>
                            </a>

                            <!-- Delete Link (Note: In a real app, you would use a dedicated JS handler for deletion) -->
                            <a href="${destroyUrl}" title="Delete User"
                               class="text-secondary hover:text-danger"
                               data-kt-action="delete"
                               data-kt-user-id="${userId}">
                               <i class="ki-duotone ki-trash">
                                  <span class="path1"></span>
                                  <span class="path2"></span>
                                  <span class="path3"></span>
                                  <span class="path4"></span>
                                  <span class="path5"></span>
                               </i>
                            </a>

                            <!-- Edit Link -->
                            <a href="${editUrl}" title="Edit User" class="text-secondary hover:text-warning">
                               <i class="ki-duotone ki-pencil">
                                  <span class="path1"></span>
                                  <span class="path2"></span>
                               </i>
                            </a>
                        </div>
                    `;
                                },
                            },
                            // ... rest of your columns

                        },

                        // Core configuration
                        pageSize: 5,
                        stateSave: true,
                        search: {
                            smart: false,
                            regex: false,
                            delay: 500, // 500ms delay before triggering the server request
                        },

                        // Add callbacks for pagination events
                        callbacks: {
                            afterDraw: function(datatable) {
                                // Add any custom behavior after drawing the table
                            },
                        },
                    });

                    // Mark as initialized and store instance
                    isInitialized = true;
                    instance = datatable;

                    return datatable;
                };

                // Public API
                return {
                    init: function() {
                        return init();
                    },
                };
            })();

            /**
             * Initialize the datatable when the page loads
             */
            // Function to safely initialize only once
            function safeInitialize() {
                var element = document.getElementById('kt_datatable_remote_source');
                if (!element) {
                    return;
                }

                var instance = KTDatatableRemoteData.init();
                if (instance) {
                    window.datatableInstance = instance;
                }
            }

            // Only attach the event listener once
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', safeInitialize, {
                    once: true
                });
            } else {
                // DOM is already loaded, initialize immediately
                setTimeout(safeInitialize, 1);
            }

            document.addEventListener('DOMContentLoaded', function() {
                const filterButton = document.getElementById('filter');
                if (!filterButton) return;

                filterButton.addEventListener('click', function() {
                    if (window.datatableInstance && typeof window.datatableInstance.reload === 'function') {
                        window.datatableInstance.reload(); // Forces the table to call API again
                    } else {
                        console.warn('KTDataTable instance not found.');
                    }
                });
            });
        </script>
    @endpush
