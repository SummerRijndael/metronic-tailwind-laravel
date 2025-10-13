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
                        value="" />
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
    <div class="kt-card-content">
        <div data-kt-datatable-table="true" id="kt_datatable_remote_source" data-kt-datatable-state-save="false">
            <div class="kt-scrollable-x-auto">
                <table class="kt-table kt-table-border table-auto" data-kt-datatable-table="true">
                    <thead>
                        <tr>
                            <th class="w-[60px] text-center">
                                <input class="kt-checkbox kt-checkbox-sm" data-kt-datatable-check="true"
                                    type="checkbox" />
                            </th>
                            <th scope="col" class="min-w-[300px]" data-kt-datatable-column="full_name">
                                <span class="kt-table-col">
                                    <span class="kt-table-col-label">
                                        Member
                                    </span>
                                    <span class="kt-table-col-sort">
                                    </span>
                                </span>
                            </th>
                            <th scope="col" class="min-w-[180px]" data-kt-datatable-column="role_name">
                                <span class="kt-table-col">
                                    <span class="kt-table-col-label">
                                        Role
                                    </span>
                                    <span class="kt-table-col-sort">
                                    </span>
                                </span>
                            </th>
                            <th scope="col" class="min-w-[180px]" data-kt-datatable-column="email">
                                <span class="kt-table-col">
                                    <span class="kt-table-col-label">
                                        Email
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
                            <td class="text-center">
                                <input class="kt-checkbox kt-checkbox-sm" data-kt-datatable-row-check="true"
                                    type="checkbox" value="1" />
                            </td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <img alt="" class="size-9 shrink-0 rounded-full"
                                        src="assets/media/avatars/300-1.png" />
                                    <div class="flex flex-col">
                                        <a class="hover:text-primary mb-px text-sm font-medium text-mono"
                                            href="#">
                                            Esther Howard
                                        </a>
                                        <a class="hover:text-primary text-sm font-normal text-secondary-foreground"
                                            href="#">
                                            esther.howard@gmail.com
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="font-normal text-foreground">
                                Editor
                            </td>
                            <td>
                                <span class="kt-badge kt-badge-destructive kt-badge-outline rounded-[30px]">
                                    <span class="kt-badge-dot size-1.5">
                                    </span>
                                    On Leave
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5 font-normal text-foreground">
                                    <img alt="" class="size-4 shrink-0 rounded-full"
                                        src="assets/media/flags/malaysia.svg" />
                                    Malaysia
                                </div>
                            </td>
                            <td class="font-normal text-foreground">
                                Week ago
                            </td>
                            <td class="text-center">
                                <div class="kt-menu flex-inline" data-kt-menu="true">
                                    <div class="kt-menu-item" data-kt-menu-item-offset="0, 10px"
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
                            <td class="text-center">
                                <input class="kt-checkbox kt-checkbox-sm" data-kt-datatable-row-check="true"
                                    type="checkbox" value="2" />
                            </td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <img alt="" class="size-9 shrink-0 rounded-full"
                                        src="assets/media/avatars/300-2.png" />
                                    <div class="flex flex-col">
                                        <a class="hover:text-primary mb-px text-sm font-medium text-mono"
                                            href="#">
                                            Cody Fisher
                                        </a>
                                        <a class="hover:text-primary text-sm font-normal text-secondary-foreground"
                                            href="#">
                                            cody.fisher@gmail.com
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="font-normal text-foreground">
                                Manager
                            </td>
                            <td>
                                <span class="kt-badge kt-badge-primary kt-badge-outline rounded-[30px]">
                                    <span class="kt-badge-dot size-1.5">
                                    </span>
                                    Remote
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5 font-normal text-foreground">
                                    <img alt="" class="size-4 shrink-0 rounded-full"
                                        src="assets/media/flags/canada.svg" />
                                    Canada
                                </div>
                            </td>
                            <td class="font-normal text-foreground">
                                Current session
                            </td>
                            <td class="text-center">
                                <div class="kt-menu flex-inline" data-kt-menu="true">
                                    <div class="kt-menu-item" data-kt-menu-item-offset="0, 10px"
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
                    <select class="kt-select w-16" data-kt-datatable-size="true" data-kt-select="" name="perpage">
                    </select>
                    per page
                </div>
                <div class="order-1 flex items-center gap-4 md:order-2">
                    <span data-kt-datatable-info="true">
                    </span>
                    <div class="kt-datatable-pagination" data-kt-datatable-pagination="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
