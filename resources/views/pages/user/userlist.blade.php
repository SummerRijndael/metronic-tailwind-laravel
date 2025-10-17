@extends('layouts.main.base')
@section('content')
    <div class="grid gap-5 lg:gap-7.5">
        <div class="kt-card kt-card-grid min-w-full">
            <div class="kt-card-header flex-wrap gap-2">
                <h3 class="kt-card-title text-sm">
                    Showing 20 of 34 users
                </h3>
                <div class="flex flex-wrap gap-2 lg:gap-5">
                    <div class="flex">
                        <label class="kt-input">
                            <i class="ki-filled ki-magnifier">
                            </i>
                            <input data-kt-datatable-search="#team_crew_table" placeholder="Search users" type="text"
                                value="">
                        </label>
                    </div>
                    <div class="flex flex-wrap gap-2.5">
                        <select name="capture" class="hidden" data-kt-select="true"
                            data-kt-select-placeholder="Select a status" data-kt-select-initialized="true">
                            <option value="1" data-kt-select-option-initialized="true">
                                Active
                            </option>
                            <option value="2" data-kt-select-option-initialized="true">
                                Disabled
                            </option>
                            <option value="2" data-kt-select-option-initialized="true">
                                Pending
                            </option>
                        </select>
                        <div data-kt-select-wrapper="" class="kt-select-wrapper w-36">
                            <div data-kt-select-display="" class="kt-select-display kt-select" tabindex="0" role="button"
                                data-selected="0" aria-haspopup="listbox" aria-expanded="false"
                                aria-label="Select an option">
                                <div data-kt-select-placeholder="" class="kt-select-placeholder">Select a status</div>
                            </div>
                            <div data-kt-select-dropdown="" class="kt-select-dropdown hidden" style="z-index: 105;">
                                <ul role="listbox" aria-label="Select an option" class="kt-select-options"
                                    data-kt-select-options="true">
                                    <li data-kt-select-option="" data-value="1" data-text="Active" class="kt-select-option"
                                        role="option" aria-selected="true">
                                        <div class="kt-select-option-text" data-kt-text-container="true">
                                            Active
                                        </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </li>
                                    <li data-kt-select-option="" data-value="2" data-text="Disabled"
                                        class="kt-select-option" role="option" aria-selected="false">
                                        <div class="kt-select-option-text" data-kt-text-container="true">
                                            Disabled
                                        </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </li>
                                    <li data-kt-select-option="" data-value="2" data-text="Pending" class="kt-select-option"
                                        role="option" aria-selected="false">
                                        <div class="kt-select-option-text" data-kt-text-container="true">
                                            Pending
                                        </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <select class="hidden" data-kt-select="true" data-kt-select-placeholder="Select a sort"
                            data-kt-select-initialized="true">
                            <option value="1" data-kt-select-option-initialized="true">
                                Latest
                            </option>
                            <option value="2" data-kt-select-option-initialized="true">
                                Older
                            </option>
                            <option value="3" data-kt-select-option-initialized="true">
                                Oldest
                            </option>
                        </select>
                        <div data-kt-select-wrapper="" class="kt-select-wrapper w-36">
                            <div data-kt-select-display="" class="kt-select-display kt-select" tabindex="0"
                                role="button" data-selected="0" aria-haspopup="listbox" aria-expanded="false"
                                aria-label="Select an option">
                                <div data-kt-select-placeholder="" class="kt-select-placeholder">Select a sort</div>
                            </div>
                            <div data-kt-select-dropdown="" class="kt-select-dropdown hidden" style="z-index: 105;">
                                <ul role="listbox" aria-label="Select an option" class="kt-select-options"
                                    data-kt-select-options="true">
                                    <li data-kt-select-option="" data-value="1" data-text="Latest"
                                        class="kt-select-option" role="option" aria-selected="true">
                                        <div class="kt-select-option-text" data-kt-text-container="true">
                                            Latest
                                        </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </li>
                                    <li data-kt-select-option="" data-value="2" data-text="Older"
                                        class="kt-select-option" role="option" aria-selected="false">
                                        <div class="kt-select-option-text" data-kt-text-container="true">
                                            Older
                                        </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </li>
                                    <li data-kt-select-option="" data-value="3" data-text="Oldest"
                                        class="kt-select-option" role="option" aria-selected="false">
                                        <div class="kt-select-option-text" data-kt-text-container="true">
                                            Oldest
                                        </div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <button class="kt-btn kt-btn-outline kt-btn-primary">
                            <i class="ki-filled ki-setting-4">
                            </i>
                            Filters
                        </button>
                    </div>
                </div>
            </div>
            <div class="kt-card-content">
                <div data-kt-datatable="true" data-kt-datatable-state-save="false" id="team_crew_table"
                    data-kt-datatable-initialized="true" class="datatable-initialized">
                    <div class="kt-scrollable-x-auto">
                        <table class="kt-table kt-table-border table-auto" data-kt-datatable-table="true">
                            <thead>
                                <tr>
                                    <th class="w-[60px] text-center">
                                        <input class="kt-checkbox kt-checkbox-sm" data-kt-datatable-check="true"
                                            type="checkbox">
                                    </th>
                                    <th class="min-w-[300px]">
                                        <span class="kt-table-col">
                                            <span class="kt-table-col-label">
                                                Member
                                            </span>
                                            <span class="kt-table-col-sort">
                                            </span>
                                        </span>
                                    </th>
                                    <th class="min-w-[180px]">
                                        <span class="kt-table-col">
                                            <span class="kt-table-col-label">
                                                Role
                                            </span>
                                            <span class="kt-table-col-sort">
                                            </span>
                                        </span>
                                    </th>
                                    <th class="min-w-[180px]">
                                        <span class="kt-table-col">
                                            <span class="kt-table-col-label">
                                                Status
                                            </span>
                                            <span class="kt-table-col-sort">
                                            </span>
                                        </span>
                                    </th>
                                    <th class="min-w-[180px]">
                                        <span class="kt-table-col">
                                            <span class="kt-table-col-label">
                                                Location
                                            </span>
                                            <span class="kt-table-col-sort">
                                            </span>
                                        </span>
                                    </th>
                                    <th class="min-w-[180px]">
                                        <span class="kt-table-col">
                                            <span class="kt-table-col-label">
                                                Activity
                                            </span>
                                            <span class="kt-table-col-sort">
                                            </span>
                                        </span>
                                    </th>
                                    <th class="w-[60px]">
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="1"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-1.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Esther Howard
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    esther.howard@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Editor</td>
                                    <td><span class="kt-badge kt-badge-destructive kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            On Leave
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/malaysia.svg">
                                            Malaysia
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Week ago</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="2"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-2.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Cody Fisher
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    cody.fisher@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Manager</td>
                                    <td><span class="kt-badge kt-badge-primary kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            Remote
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/canada.svg">
                                            Canada
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Current session</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="3"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-3.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Tyler Hero
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    tyler.hero@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Super Admin</td>
                                    <td><span class="kt-badge kt-badge-success kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            In Office
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/estonia.svg">
                                            Estonia
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Current session</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="4"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-4.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Robert Fox
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    robert.fox@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Developer</td>
                                    <td><span class="kt-badge kt-badge-success kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            In Office
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/united-states.svg">
                                            USA
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Today, 15:02</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="5"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-5.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Leslie Alexander
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    leslie.alexander@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Super Admin</td>
                                    <td><span class="kt-badge kt-badge-success kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            In Office
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/india.svg">
                                            India
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Month ago</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="6"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-6.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    John Smith
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    john.smith@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Designer</td>
                                    <td><span class="kt-badge kt-badge-destructive kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            On Leave
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/australia.svg">
                                            Australia
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Yesterday, 14:23</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="7"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-7.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Emily Johnson
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    emily.johnson@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Developer</td>
                                    <td><span class="kt-badge kt-badge-primary kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            Remote
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/france.svg">
                                            France
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Today, 10:12</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="8"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-8.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Michael Brown
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    michael.brown@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">QA Engineer</td>
                                    <td><span class="kt-badge kt-badge-success kt-badge-outline rounded-[30px]">
                                            <span class="size-1.5 rounded-full bg-green-500">
                                            </span>
                                            In Office
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/germany.svg">
                                            Germany
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Today, 09:45</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="9"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-9.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    William Davis
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    william.davis@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Support</td>
                                    <td><span class="kt-badge kt-badge-destructive kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            On Leave
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/spain.svg">
                                            Spain
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Last week</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input class="kt-checkbox kt-checkbox-sm"
                                            data-kt-datatable-row-check="true" type="checkbox" value="10"></td>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <img alt="" class="size-9 shrink-0 rounded-full"
                                                src="assets/media/avatars/300-10.png">
                                            <div class="flex flex-col">
                                                <a class="mb-px text-sm font-medium text-mono hover:text-primary"
                                                    href="#">
                                                    Olivia Martinez
                                                </a>
                                                <a class="text-sm font-normal text-secondary-foreground hover:text-primary"
                                                    href="#">
                                                    olivia.martinez@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Product Manager</td>
                                    <td><span class="kt-badge kt-badge-primary kt-badge-outline rounded-[30px]">
                                            <span class="kt-badge-dot size-1.5">
                                            </span>
                                            Remote
                                        </span></td>
                                    <td>
                                        <div class="flex items-center gap-1.5 font-normal text-foreground">
                                            <img alt="" class="size-4 shrink-0 rounded-full"
                                                src="assets/media/flags/italy.svg">
                                            Italy
                                        </div>
                                    </td>
                                    <td class="font-normal text-foreground">Current session</td>
                                    <td class="text-center">
                                        <div class="kt-menu flex-inline" data-kt-menu="true">
                                            <div class="kt-menu-item kt-menu-item-dropdown"
                                                data-kt-menu-item-offset="0, 10px"
                                                data-kt-menu-item-placement="bottom-end"
                                                data-kt-menu-item-placement-rtl="bottom-start"
                                                data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                                                <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                    <i class="ki-filled ki-dots-vertical text-lg">
                                                    </i>
                                                </button>
                                                <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                    data-kt-menu-dismiss="true">
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-search-list">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                View
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-file-up">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Export
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-pencil">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-copy">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Make a copy
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="kt-menu-separator">
                                                    </div>
                                                    <div class="kt-menu-item">
                                                        <a class="kt-menu-link" href="#">
                                                            <span class="kt-menu-icon">
                                                                <i class="ki-filled ki-trash">
                                                                </i>
                                                            </span>
                                                            <span class="kt-menu-title">
                                                                Remove
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="kt-card-footer flex-col justify-center gap-5 text-sm font-medium text-secondary-foreground md:flex-row md:justify-between">
                        <div class="order-2 flex items-center gap-2 md:order-1">
                            Show
                            <select class="hidden" data-kt-datatable-size="true" data-kt-select="" name="perpage"
                                data-kt-select-initialized="true">
                                <option value="5" data-kt-select-option-initialized="true">5</option>
                                <option value="10" data-kt-select-option-initialized="true">10</option>
                                <option value="20" data-kt-select-option-initialized="true">20</option>
                                <option value="30" data-kt-select-option-initialized="true">30</option>
                                <option value="50" data-kt-select-option-initialized="true">50</option>
                            </select>
                            <div data-kt-select-wrapper="" class="kt-select-wrapper w-16">
                                <div data-kt-select-display="" class="kt-select-display kt-select" tabindex="0"
                                    role="button" data-selected="0" aria-haspopup="listbox" aria-expanded="false"
                                    aria-label="Select an option">10</div>
                                <div data-kt-select-dropdown="" class="kt-select-dropdown hidden" style="z-index: 105;">
                                    <ul role="listbox" aria-label="Select an option" class="kt-select-options"
                                        data-kt-select-options="true">
                                        <li data-kt-select-option="" data-value="5" data-text="5"
                                            class="kt-select-option" role="option" aria-selected="false">
                                            <div class="kt-select-option-text" data-kt-text-container="true">5</div><svg
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </li>
                                        <li data-kt-select-option="" data-value="10" data-text="10"
                                            class="kt-select-option selected" role="option" aria-selected="true">
                                            <div class="kt-select-option-text" data-kt-text-container="true">10</div><svg
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </li>
                                        <li data-kt-select-option="" data-value="20" data-text="20"
                                            class="kt-select-option" role="option" aria-selected="false">
                                            <div class="kt-select-option-text" data-kt-text-container="true">20</div><svg
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </li>
                                        <li data-kt-select-option="" data-value="30" data-text="30"
                                            class="kt-select-option" role="option" aria-selected="false">
                                            <div class="kt-select-option-text" data-kt-text-container="true">30</div><svg
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </li>
                                        <li data-kt-select-option="" data-value="50" data-text="50"
                                            class="kt-select-option" role="option" aria-selected="false">
                                            <div class="kt-select-option-text" data-kt-text-container="true">50</div><svg
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="kt-select-option-selected:block ms-auto hidden size-3.5 text-primary">
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            per page
                        </div>
                        <div class="order-1 flex items-center gap-4 md:order-2">
                            <span data-kt-datatable-info="true">1-10 of 34</span>
                            <div class="kt-datatable-pagination" data-kt-datatable-pagination="true"><button
                                    class="kt-datatable-pagination-button kt-datatable-pagination-prev disabled"
                                    disabled="">
                                    <svg class="size-3.5 shrink-0 rtl:rotate-180 rtl:transform" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.86501 16.7882V12.8481H21.1459C21.3724 12.8481 21.5897 12.7581 21.7498 12.5979C21.91 12.4378 22 12.2205 22 11.994C22 11.7675 21.91 11.5503 21.7498 11.3901C21.5897 11.2299 21.3724 11.1399 21.1459 11.1399H8.86501V7.2112C8.86628 7.10375 8.83517 6.9984 8.77573 6.90887C8.7163 6.81934 8.63129 6.74978 8.53177 6.70923C8.43225 6.66869 8.32283 6.65904 8.21775 6.68155C8.11267 6.70405 8.0168 6.75766 7.94262 6.83541L2.15981 11.6182C2.1092 11.668 2.06901 11.7274 2.04157 11.7929C2.01413 11.8584 2 11.9287 2 11.9997C2 12.0707 2.01413 12.141 2.04157 12.2065C2.06901 12.272 2.1092 12.3314 2.15981 12.3812L7.94262 17.164C8.0168 17.2417 8.11267 17.2953 8.21775 17.3178C8.32283 17.3403 8.43225 17.3307 8.53177 17.2902C8.63129 17.2496 8.7163 17.18 8.77573 17.0905C8.83517 17.001 8.86628 16.8956 8.86501 16.7882Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </button><button class="kt-datatable-pagination-button active disabled"
                                    disabled="">1</button><button
                                    class="kt-datatable-pagination-button">2</button><button
                                    class="kt-datatable-pagination-button">3</button><button
                                    class="kt-datatable-pagination-button kt-datatable-pagination-more">...</button><button
                                    class="kt-datatable-pagination-button kt-datatable-pagination-next">
                                    <svg class="size-3.5 shrink-0 rtl:rotate-180 rtl:transform" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.135 7.21144V11.1516H2.85407C2.62756 11.1516 2.41032 11.2415 2.25015 11.4017C2.08998 11.5619 2 11.7791 2 12.0056C2 12.2321 2.08998 12.4494 2.25015 12.6096C2.41032 12.7697 2.62756 12.8597 2.85407 12.8597H15.135V16.7884C15.1337 16.8959 15.1648 17.0012 15.2243 17.0908C15.2837 17.1803 15.3687 17.2499 15.4682 17.2904C15.5677 17.3309 15.6772 17.3406 15.7822 17.3181C15.8873 17.2956 15.9832 17.242 16.0574 17.1642L21.8402 12.3814C21.8908 12.3316 21.931 12.2722 21.9584 12.2067C21.9859 12.1412 22 12.0709 22 11.9999C22 11.9289 21.9859 11.8586 21.9584 11.7931C21.931 11.7276 21.8908 11.6683 21.8402 11.6185L16.0574 6.83565C15.9832 6.75791 15.8873 6.70429 15.7822 6.68179C15.6772 6.65929 15.5677 6.66893 15.4682 6.70948C15.3687 6.75002 15.2837 6.81959 15.2243 6.90911C15.1648 6.99864 15.1337 7.10399 15.135 7.21144Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-card">
            <div class="kt-card-header">
                <h3 class="kt-card-title">
                    FAQ
                </h3>
            </div>
            <div class="kt-card-content py-3">
                <div data-kt-accordion="true" data-kt-accordion-expand-all="true"
                    data-kt-accordion-initialized="true">
                    <div class="kt-accordion-item not-last:border-b border-b-border" data-kt-accordion-item="true">
                        <button aria-controls="faq_1_content" class="kt-accordion-toggle py-4"
                            data-kt-accordion-toggle="#faq_1_content">
                            <span class="text-base text-mono">
                                How is pricing determined for each plan?
                            </span>
                            <span class="kt-accordion-active:hidden inline-flex">
                                <i class="ki-filled ki-plus text-sm text-muted-foreground">
                                </i>
                            </span>
                            <span class="kt-accordion-active:inline-flex hidden">
                                <i class="ki-filled ki-minus text-sm text-muted-foreground">
                                </i>
                            </span>
                        </button>
                        <div class="kt-accordion-content hidden" id="faq_1_content">
                            <div class="pb-4 text-base text-secondary-foreground">
                                Metronic embraces flexible licensing options that empower you to choose the perfect fit for
                                your project's needs and budget. Understanding the factors influencing each plan's pricing
                                helps you make an informed decision. Metronic embraces flexible licensing options that
                                empower you to choose the perfect fit for your project's needs and budget. Understanding the
                                factors influencing each plan's pricing helps you make an informed decision. Metronic
                                embraces flexible licensing options that empower you to choose the perfect fit for your
                                project's needs and budget. Understanding the factors influencing each plan's pricing helps
                                you make an informed decision
                            </div>
                        </div>
                    </div>
                    <div class="kt-accordion-item not-last:border-b border-b-border" data-kt-accordion-item="true">
                        <button aria-controls="faq_2_content" class="kt-accordion-toggle py-4"
                            data-kt-accordion-toggle="#faq_2_content">
                            <span class="text-base text-mono">
                                What payment methods are accepted for subscriptions?
                            </span>
                            <span class="kt-accordion-active:hidden inline-flex">
                                <i class="ki-filled ki-plus text-sm text-muted-foreground">
                                </i>
                            </span>
                            <span class="kt-accordion-active:inline-flex hidden">
                                <i class="ki-filled ki-minus text-sm text-muted-foreground">
                                </i>
                            </span>
                        </button>
                        <div class="kt-accordion-content hidden" id="faq_2_content">
                            <div class="pb-4 text-base text-secondary-foreground">
                                Metronic embraces flexible licensing options that empower you to choose the perfect fit for
                                your project's needs and budget. Understanding the factors influencing each plan's pricing
                                helps you make an informed decision
                            </div>
                        </div>
                    </div>
                    <div class="kt-accordion-item not-last:border-b border-b-border" data-kt-accordion-item="true">
                        <button aria-controls="faq_3_content" class="kt-accordion-toggle py-4"
                            data-kt-accordion-toggle="#faq_3_content">
                            <span class="text-base text-mono">
                                Are there any hidden fees in the pricing?
                            </span>
                            <span class="kt-accordion-active:hidden inline-flex">
                                <i class="ki-filled ki-plus text-sm text-muted-foreground">
                                </i>
                            </span>
                            <span class="kt-accordion-active:inline-flex hidden">
                                <i class="ki-filled ki-minus text-sm text-muted-foreground">
                                </i>
                            </span>
                        </button>
                        <div class="kt-accordion-content hidden" id="faq_3_content">
                            <div class="pb-4 text-base text-secondary-foreground">
                                Metronic embraces flexible licensing options that empower you to choose the perfect fit for
                                your project's needs and budget. Understanding the factors influencing each plan's pricing
                                helps you make an informed decision
                            </div>
                        </div>
                    </div>
                    <div class="kt-accordion-item not-last:border-b border-b-border" data-kt-accordion-item="true">
                        <button aria-controls="faq_4_content" class="kt-accordion-toggle py-4"
                            data-kt-accordion-toggle="#faq_4_content">
                            <span class="text-base text-mono">
                                Is there a discount for annual subscriptions?
                            </span>
                            <span class="kt-accordion-active:hidden inline-flex">
                                <i class="ki-filled ki-plus text-sm text-muted-foreground">
                                </i>
                            </span>
                            <span class="kt-accordion-active:inline-flex hidden">
                                <i class="ki-filled ki-minus text-sm text-muted-foreground">
                                </i>
                            </span>
                        </button>
                        <div class="kt-accordion-content hidden" id="faq_4_content">
                            <div class="pb-4 text-base text-secondary-foreground">
                                Metronic embraces flexible licensing options that empower you to choose the perfect fit for
                                your project's needs and budget. Understanding the factors influencing each plan's pricing
                                helps you make an informed decision
                            </div>
                        </div>
                    </div>
                    <div class="kt-accordion-item not-last:border-b border-b-border" data-kt-accordion-item="true">
                        <button aria-controls="faq_5_content" class="kt-accordion-toggle py-4"
                            data-kt-accordion-toggle="#faq_5_content">
                            <span class="text-base text-mono">
                                Do you offer refunds on subscription cancellations?
                            </span>
                            <span class="kt-accordion-active:hidden inline-flex">
                                <i class="ki-filled ki-plus text-sm text-muted-foreground">
                                </i>
                            </span>
                            <span class="kt-accordion-active:inline-flex hidden">
                                <i class="ki-filled ki-minus text-sm text-muted-foreground">
                                </i>
                            </span>
                        </button>
                        <div class="kt-accordion-content hidden" id="faq_5_content">
                            <div class="pb-4 text-base text-secondary-foreground">
                                Metronic embraces flexible licensing options that empower you to choose the perfect fit for
                                your project's needs and budget. Understanding the factors influencing each plan's pricing
                                helps you make an informed decision
                            </div>
                        </div>
                    </div>
                    <div class="kt-accordion-item not-last:border-b border-b-border" data-kt-accordion-item="true">
                        <button aria-controls="faq_6_content" class="kt-accordion-toggle py-4"
                            data-kt-accordion-toggle="#faq_6_content">
                            <span class="text-base text-mono">
                                Can I add extra features to my current plan?
                            </span>
                            <span class="kt-accordion-active:hidden inline-flex">
                                <i class="ki-filled ki-plus text-sm text-muted-foreground">
                                </i>
                            </span>
                            <span class="kt-accordion-active:inline-flex hidden">
                                <i class="ki-filled ki-minus text-sm text-muted-foreground">
                                </i>
                            </span>
                        </button>
                        <div class="kt-accordion-content hidden" id="faq_6_content">
                            <div class="pb-4 text-base text-secondary-foreground">
                                Metronic embraces flexible licensing options that empower you to choose the perfect fit for
                                your project's needs and budget. Understanding the factors influencing each plan's pricing
                                helps you make an informed decision
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid gap-5 lg:grid-cols-2 lg:gap-7.5">
            <div class="kt-card">
                <div class="kt-card-content lg:pr-12.5 px-10 py-7.5">
                    <div class="flex flex-wrap items-center gap-6 md:flex-nowrap md:gap-10">
                        <div class="flex flex-col items-start gap-3">
                            <h2 class="text-xl font-medium text-mono">
                                Questions ?
                            </h2>
                            <p class="leading-5.5 mb-2.5 text-sm text-foreground">
                                Visit our Help Center for detailed assistance on billing, payments, and subscriptions.
                            </p>
                        </div>
                        <img alt="image" class="max-h-[150px] dark:hidden" src="assets/media/illustrations/29.svg">
                        <img alt="image" class="light:hidden max-h-[150px]"
                            src="assets/media/illustrations/29-dark.svg">
                    </div>
                </div>
                <div class="kt-card-footer justify-center">
                    <a class="kt-link kt-link-underlined kt-link-dashed" href="">
                        Go to Help Center
                    </a>
                </div>
            </div>
            <div class="kt-card">
                <div class="kt-card-content lg:pr-12.5 px-10 py-7.5">
                    <div class="flex flex-wrap items-center gap-6 md:flex-nowrap md:gap-10">
                        <div class="flex flex-col items-start gap-3">
                            <h2 class="text-xl font-medium text-mono">
                                Contact Support
                            </h2>
                            <p class="leading-5.5 mb-2.5 text-sm text-foreground">
                                Need assistance? Contact our support team for prompt, personalized help your queries &amp;
                                concerns.
                            </p>
                        </div>
                        <img alt="image" class="max-h-[150px] dark:hidden" src="assets/media/illustrations/31.svg">
                        <img alt="image" class="light:hidden max-h-[150px]"
                            src="assets/media/illustrations/31-dark.svg">
                    </div>
                </div>
                <div class="kt-card-footer justify-center">
                    <a class="kt-link kt-link-underlined kt-link-dashed" href="https://devs.keenthemes.com/unresolved">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
