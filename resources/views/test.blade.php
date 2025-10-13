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




    <div class="grid w-full space-y-5">
        <div class="kt-card shadow-md">
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
                        <select class="kt-select w-36" data-kt-select="true" data-kt-select-placeholder="Select a status">
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
                        <select class="kt-select w-36" data-kt-select="true" data-kt-select-placeholder="Select a sort">
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
                        <button class="kt-btn kt-btn-outline kt-btn-primary">
                            <i class="ki-filled ki-setting-4">
                            </i>
                            Filters
                        </button>
                    </div>
                </div>
            </div>
            <div class="kt-card-table" id="kt_datatable_remote_source" data-kt-datatable-page-size="5"
                data-kt-datatable-state-save="true">
                <div class="kt-table-wrapper kt-scrollable">
                    <table class="kt-table" data-kt-datatable-table="true">
                        <thead>
                            <tr>
                                <th scope="col" class="w-20" data-kt-datatable-column="id">
                                    <span class="kt-table-col"><span class="kt-table-col-label">ID</span><span
                                            class="kt-table-col-sort"></span></span>
                                </th>
                                <th scope="col" class="w-20" data-kt-datatable-column="full_name">
                                    <span class="kt-table-col"><span class="kt-table-col-label">Member</span><span
                                            class="kt-table-col-sort"></span></span>
                                </th>
                                <th scope="col" class="w-20" data-kt-datatable-column="email">
                                    <span class="kt-table-col"><span class="kt-table-col-label">Email</span><span
                                            class="kt-table-col-sort"></span></span>
                                </th>
                                <th scope="col" class="w-20" data-kt-datatable-column="role_name">
                                    <span class="kt-table-col"><span class="kt-table-col-label">Role</span><span
                                            class="kt-table-col-sort"></span></span>
                                </th>
                                <th scope="col" class="w-20" data-kt-datatable-column="status_value">
                                    <span class="kt-table-col"><span class="kt-table-col-label">Account Created</span><span
                                            class="kt-table-col-sort"></span></span>
                                </th>
                                <th scope="col" class="w-20" data-kt-datatable-column="">
                                    <span class="kt-table-col"><span class="kt-table-col-label">Actions</span><span
                                            class="kt-table-col-sort"></span></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!--begin:pagination-->
                <div class="kt-datatable-toolbar">
                    <div class="kt-datatable-length">
                        Show<select class="kt-select kt-select-sm w-16" name="perpage"
                            data-kt-datatable-size="true"></select>per page
                    </div>
                    <div class="kt-datatable-info">
                        <span data-kt-datatable-info="true"></span>
                        <div class="kt-datatable-pagination" data-kt-datatable-pagination="true"></div>
                    </div>
                </div>
                <!--end:pagination-->
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
         * This example demonstrates how to initialize a KTDataTable with a remote API data source,
         * including the necessary 'search' configuration for automatic debouncing.
         */
        var KTDatatableRemoteDataDemo = (function() {
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
                            title: 'id',

                        },

                        full_name: {
                            title: 'Fullname',
                            render: function(value, row) {
                                const avatarPathFragment = row.avatar;
                                let imagePath = '';

                                // 1. Determine the correct image path
                                if (!avatarPathFragment || avatarPathFragment === 'blank.png') {
                                    imagePath = ASSET_BASE_PATH + '/blank.png';
                                } else {
                                    // Assumes avatarPathFragment is 'encryptedfolder/encryptedname.png'
                                    imagePath = STORAGE_BASE_PATH + '/' + avatarPathFragment;
                                }

                                // 2. Generate the avatar HTML
                                const avatarHtml = `
                                <div class="w-8 h-8 overflow-hidden rounded-full flex items-center justify-center bg-gray-200 mr-5 shrink-0 shadow-md">
                                    <img src="${imagePath}"
                                         alt="${row.full_name}'s avatar"
                                         class="rounded-full w-8 h-8 object-cover"">
                                </div>
                            `;

                                // 3. Combine avatar and full name
                                // The flex layout ensures the image and text are horizontally aligned
                                return `
                                <div class="flex items-center">
                                    ${avatarHtml}
                                    <span class="text-sm font-medium text-gray-700 whitespace-nowrap">
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
                        created_at: {
                            title: 'Created',
                        },
                    },

                    sort: {

                    },

                    // Core configuration
                    pageSize: 5,
                    stateSave: true,

                    // **CRITICAL SEARCH CONFIGURATION**
                    // This tells KTDataTable to automatically listen for the input
                    // linked by data-kt-datatable-search="#kt_datatable_remote_source"
                    // and debounce the request.
                    search: {
                        smart: false,
                        regex: false,
                        delay: 500, // 500ms delay before triggering the server request
                    },

                    // Add callbacks for pagination events
                    callbacks: {
                        afterDraw: function(datatable) {
                            // Add any custom behavior after drawing the table
                            console.log('sdasdas');
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

            var instance = KTDatatableRemoteDataDemo.init();
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
    </script>
@endpush
