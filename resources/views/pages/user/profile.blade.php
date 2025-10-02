@extends('layouts.main.base')
@section('content')

<div class="grid grid-cols-1 xl:grid-cols-1 gap-5 lg:gap-7.5">
    <div class="col-span-1">
        <div class="grid gap-5 lg:gap-7.5">
            {{-- Personal Info Card --}}
<div class="kt-card min-w-full">
    <div class="kt-card-header">
        <h3 class="kt-card-title">
            Personal Info
        </h3>
    </div>
    <!-- Added border and border-separate/border-spacing-0 for table view -->
    <div class="kt-card-table kt-scrollable-x-auto pb-3">
        <table class="kt-table align-middle text-sm text-muted-foreground border border-border border-collapse w-full">
            <tbody>
                <!--
                    New structure: Image column is now the 3rd column, starting here,
                    and spans 4 rows (Name, Availability, Birthday, Gender).
                -->
                <tr class="border-b border-border">
                    <td class="py-2 w-32 text-secondary-foreground font-normal border border-border">
                        Name
                    </td>
                    <td class="py-2 text-foreground font-normal text-sm border border-border">
                        {{ $user->name }}{{ $user->lastname ? ' ' . $user->lastname : '' }}
                    </td>
                    <!-- MOVED IMAGE CELL: Set rowspan="4" to cover 4 rows -->
                    <!-- UPDATED w-32 to w-40 for more padding for the larger image -->
                    <td class="py-2 text-center border border-border w-40" rowspan="4">
                        <!-- Use flex-col to stack image and label vertically, justify-center to center content -->
                        <div class="flex flex-col items-center justify-center h-full">
                            <!-- BEGIN FIX: Sizing and rounding moved to a container div with overflow-hidden -->
                            <!-- UPDATED size-[120px] to size-[150px] to make the circle bigger -->
                            <div class="size-[130px] rounded-full border-3 border-green-500 overflow-hidden shrink-0 mb-2">
                                <!-- Image is now set to fill the container 100% and uses object-cover to crop -->
                                <img class="w-full h-full object-cover" src="{{ asset('assets/media/avatars/' . $user->avatar) }}" alt="image">
                            </div>
                            <!-- END FIX -->
                            <!-- New Photo Label under the image -->
                            <div class="text-secondary-foreground text-xs font-medium">Photo</div>
                        </div>
                    </td>
                </tr>
                <!-- The image from the row above spans this row -->
                <tr class="border-b border-border">
                    <td class="py-3 w-32 text-secondary-foreground font-normal border border-border">
                        Availability
                    </td>
                    <td class="py-3 text-foreground font-normal border border-border">
                        <span class="kt-badge kt-badge-sm kt-badge-outline {{ $user->is_active ? 'kt-badge-success' : 'kt-badge-destructive' }}">
                            {{ $user->is_active ? 'Available now' : 'Not available' }}
                        </span>
                    </td>
                </tr>
                <!-- The image from the row above spans this row -->
                <tr class="border-b border-border">
                    <td class="py-3 text-secondary-foreground font-normal border border-border">
                        Birthday
                    </td>
                    <td class="py-3 text-secondary-foreground text-sm font-normal border border-border">
                        {{ $user->bday ? $user->bday->format('F j, Y') : 'Not specified' }}
                    </td>
                </tr>
                <!-- The image from the row above spans this row -->
                <tr class="border-b border-border">
                    <td class="py-3 text-secondary-foreground font-normal border border-border">
                        Gender
                    </td>
                    <td class="py-3 text-secondary-foreground text-sm font-normal border border-border">
                        {{ $user->sex ? $user->sex : 'Not specified' }}
                    </td>
                </tr>
                <!-- This row is after the rowspan, so it needs its own 3rd column cell for the "Add" link -->
                <tr>
                    <td class="py-3 border border-border">
                        Address
                    </td>
                    <td class="py-3 text-secondary-foreground text-sm font-normal border border-border">
                        You have no an address yet
                    </td>
                    <td class="py-3 text-right border border-border">
                        <a class="kt-link kt-link-underlined kt-link-dashed" href="#">
                            Add
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>




            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

                {{-- Basic Settings Card --}}
                <div class="kt-card min-w-full">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">
                            Basic Settings
                        </h3>
                        <div class="flex items-center gap-2">
                            <label class="kt-label">
                                Public Profile
                                <input checked="" class="kt-switch kt-switch-sm" name="check" type="checkbox" value="1">
                            </label>
                        </div>
                    </div>
                    <div class="kt-card-table kt-scrollable-x-auto pb-3">
                        <table class="kt-table align-middle text-sm text-muted-foreground">
                            <tbody>
                                <tr>
                                    <td class="py-2 min-w-36 text-secondary-foreground font-normal">
                                        Email
                                    </td>
                                    <td class="py-2 min-w-60">
                                        <a class="text-foreground font-normal text-sm hover:text-primary" href="#">
                                            {{ $user->email }}
                                        </a>
                                    </td>
                                    <td class="py-2 max-w-16 text-end">
                                        <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                            <i class="ki-filled ki-notepad-edit">
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-gray-600 font-normal">
                                        Password Updated
                                    </td>
                                    <td class="py-2 text-secondary-foreground font-normal">
                                         {{ $user->password_changed_at ? $user->password_changed_at->diffForHumans() : 'Never' }}
                                    </td>
                                    <td class="py-2 text-end">
                                        <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                            <i class="ki-filled ki-notepad-edit">
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3.5 text-gray-600 font-normal">
                                        2FA
                                    </td>
                                    <td class="py-3.5 text-secondary-foreground font-normal">
                                        {{ $user->two_factor_confirmed_at ? $user->two_factor_confirmed_at->format('F j, Y') : 'To be set' }}
                                    </td>
                                    <td class="py-3 text-end">
                                        @if(!$user->two_factor_confirmed_at)
                                            <a class="kt-link kt-link-underlined kt-link-dashed" href="#">
                                                Setup
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-gray-600 font-normal">
                                        Sign-in with
                                    </td>
                                    <td class="py-0.5">
                                        <div class="flex items-center gap-2.5">
                                            <a class="flex items-center justify-center size-8 bg-background rounded-full border border-input" href="#">
                                                <img alt="" class="size-4" src="assets/media/brand-logos/google.svg">
                                            </a>
                                            <a class="flex items-center justify-center size-8 bg-background rounded-full border border-input" href="#">
                                                <img alt="" class="size-4" src="assets/media/brand-logos/facebook.svg">
                                            </a>
                                            <a class="flex items-center justify-center size-8 bg-background rounded-full border border-input" href="#">
                                                <img alt="product logo" class="dark:hidden h-4" src="assets/media/brand-logos/apple-black.svg">
                                                <img alt="product logo" class="light:hidden h-4" src="assets/media/brand-logos/apple-white.svg">
                                            </a>
                                        </div>
                                    </td>
                                    <td class="py-2 text-end">
                                        <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                            <i class="ki-filled ki-notepad-edit">
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-gray-600 font-normal">
                                       SOON
                                    </td>
                                    <td class="py-3 text-secondary-foreground font-normal">
                                        SOON
                                    </td>
                                    <td class="py-3 text-end">
                                        <a class="kt-link kt-link-underlined kt-link-dashed" href="#">
                                            SOON
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 text-secondary-foreground font-normal">
                                       SOON
                                    </td>
                                    <td class="py-0.5">
                                        SOON
                                    </td>
                                    <td class="py-2 text-end">
                                        <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                            <i class="ki-filled ki-notepad-edit">
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-secondary-foreground text-sm font-normal">
                                       SOON
                                    </td>
                                    <td class="py-3 text-secondary-foreground text-sm font-normal">
                                        SOON
                                    </td>
                                    <td class="py-3 text-end">
                                        <a class="kt-link kt-link-underlined kt-link-dashed" href="#">
                                           SOON
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Activity Card - FIX APPLIED HERE --}}
                <div class="kt-card flex flex-col h-96">
                    <div class="kt-card-header flex-shrink-0">
                        <h3 class="kt-card-title">Activity</h3>
                        <div class="flex items-center gap-2">
                            <label class="group text-sm font-medium inline-flex items-center gap-2">
                                <span class="inline-flex items-center gap-2">
                                    Auto refresh:
                                    <span class="group-has-checked:hidden">Off</span>
                                    <span class="hidden group-has-checked:inline">On</span>
                                </span>
                                <input checked class="kt-switch kt-switch-sm" name="check" type="checkbox" value="1">
                            </label>
                        </div>
                    </div>

                    {{-- KEY FIX: flex-grow, overflow-y-auto, and the essential min-h-0 --}}
                    <div class="kt-card-content flex-grow overflow-y-auto min-h-0 scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-gray-300">
                        @forelse($activities as $activity)
                            @php
                                $meta = $activity->meta ?? [];
                                $iconClass = $meta['icon'] ?? 'ki-filled ki-people';
                                $bgClass   = $meta['color'] ?? 'bg-accent/60';
                            @endphp

                            <div class="flex items-start relative">
                                <div class="w-9 start-0 top-9 absolute bottom-0 rtl:-translate-x-1/2 translate-x-1/2 border-s border-s-input"></div>

                                <div class="flex items-center justify-center shrink-0 rounded-full {{ $bgClass }} border border-input size-9 text-secondary-foreground">
                                    <i class="{{ $iconClass }} text-base"></i>
                                </div>

                                <div class="ps-2.5 mb-7 text-base grow">
                                    <div class="flex flex-col">
                                        <div class="text-sm text-foreground">
                                            {{ $activity->description }}
                                        </div>
                                        <span class="text-xs text-secondary-foreground">
                                            {{ $activity->created_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-secondary-foreground">No activities yet.</p>
                        @endforelse
                    </div>

                    <div class="kt-card-footer justify-center flex-shrink-0">
                        <a class="kt-link kt-link-underlined kt-link-dashed" href="#">
                            All-time Activities
                        </a>
                    </div>
                </div>

            </div> {{-- End of grid grid-cols-1 xl:grid-cols-2 gap-4 --}}


            {{-- Account Details Card --}}
            <div class="kt-card min-w-full">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        Account details
                    </h3>
                    <div class="flex items-center gap-2">
                        <label class="kt-label">
                            Available now
                            <input checked="" class="kt-switch kt-switch-sm" name="check" type="checkbox" value="1">
                        </label>
                    </div>
                </div>
                <div class="kt-card-table kt-scrollable-x-auto pb-3">
                    <table class="kt-table align-middle text-sm text-muted-foreground">
                        <tbody>
                            <tr>
                                <td class="py-2 min-w-36 text-gray-600 font-normal">
                                    Role:
                                </td>
                                <td class="py-2 min-w-72 w-full text-foreground font-normal">
                                    {{ $user->role_type }}
                                </td>
                                <td class="py-2 text-end min-w-24">
                                    <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                        <i class="ki-filled ki-notepad-edit">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 text-secondary-foreground font-normal">
                                    Account created:
                                </td>
                                <td class="py-2 text-foreground font-normal">
                                    {{ $user->created_at->format('l, F j, Y g:i A') }}
                                </td>
                                <td class="py-2 text-end">
                                    <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                        <i class="ki-filled ki-notepad-edit">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-600 font-normal">
                                    Last Updated
                                </td>
                                <td class="py-2 text-foreground font-normal">
                                    {{ $user->updated_at->format('l, F j, Y g:i A') }}
                                </td>
                                <td class="py-2 text-end">
                                    <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                        <i class="ki-filled ki-notepad-edit">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-secondary-foreground font-normal">
                                    Permissions
                                </td>
                                <td class="py-3 text-secondary-foreground">
                                    <div class="flex flex-wrap gap-2.5">

                                        @foreach ($user->permissions as $permission => $value)
                                            <span class="kt-badge {{ $value ? 'kt-badge-success' : 'kt-badge-destructive' }}">
                                                {{ $permission }}
                                            </span>
                                        @endforeach

                                    </div>
                                </td>
                                <td class="py-3 text-end">
                                    <a class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost kt-btn-primary" href="#">
                                        <i class="ki-filled ki-notepad-edit">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 text-secondary-foreground font-normal">
                                    Email verfied at
                                </td>
                                <td class="py-4 text-foreground font-normal">
                                    {{ $user->email_verified_at ? $user->email_verified_at->format('l, F j, Y g:i A') : 'Not verified' }}
                                </td>
                                <td class="py-4 text-end">
                                    @if (!$user->email_verified_at)
                                        <button type="button" class="kt-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings" aria-hidden="true">
                                                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
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

            {{-- Badges Card --}}
            <div class="kt-card">
                <div class="kt-card-header gap-2">
                    <h3 class="kt-card-title">
                        Badges
                    </h3>
                    <div class="kt-menu" data-kt-menu="true">
                        <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="0, 10px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-placement-rtl="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click">
                            <button class="kt-menu-toggle kt-btn kt-btn-icon kt-btn-ghost">
                                <i class="ki-filled ki-information-2">
                                </i>
                            </button>
                            <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]" data-kt-menu-dismiss="true">
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" href="html/demo10/account/home/settings-plain.html">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-add-files">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Add
                                        </span>
                                    </a>
                                </div>
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" href="html/demo10/account/members/import-members.html">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-file-down">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Import
                                        </span>
                                    </a>
                                </div>
                                <div class="kt-menu-item kt-menu-item-dropdown" data-kt-menu-item-offset="-15px, 0" data-kt-menu-item-placement="right-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click|lg:hover">
                                    <div class="kt-menu-link">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-file-up">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Export
                                        </span>
                                        <span class="kt-menu-arrow">
                                            <i class="ki-filled ki-right text-xs rtl:transform rtl:rotate-180">
                                            </i>
                                        </span>
                                    </div>
                                    <div class="kt-menu-dropdown kt-menu-default w-full max-w-[125px]">
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link" href="html/demo10/account/home/settings-sidebar.html">
                                                <span class="kt-menu-title">
                                                    PDF
                                                </span>
                                            </a>
                                        </div>
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link" href="html/demo10/account/home/settings-sidebar.html">
                                                <span class="kt-menu-title">
                                                    CVS
                                                </span>
                                            </a>
                                        </div>
                                        <div class="kt-menu-item">
                                            <a class="kt-menu-link" href="html/demo10/account/home/settings-sidebar.html">
                                                <span class="kt-menu-title">
                                                    Excel
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-menu-item">
                                    <a class="kt-menu-link" href="html/demo10/account/security/privacy-settings.html">
                                        <span class="kt-menu-icon">
                                            <i class="ki-filled ki-setting-3">
                                            </i>
                                        </span>
                                        <span class="kt-menu-title">
                                            Settings
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-card-content pb-7.5">
                    <div class="grid gap-2.5">
                        <div class="flex items-center justify-between flex-wrap group border border-border rounded-xl gap-2 px-3.5 py-2.5">
                            <div class="flex items-center flex-wrap gap-2.5">
                                <div class="relative size-[50px] shrink-0">
                                    <svg class="w-full h-full stroke-primary/10 fill-primary/5" fill="none" height="48" viewBox="0 0 44 48" width="44" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 2.4641C19.7128 0.320509 24.2872 0.320508 28 2.4641L37.6506 8.0359C41.3634 10.1795 43.6506 14.141 43.6506 
			18.4282V29.5718C43.6506 33.859 41.3634 37.8205 37.6506 39.9641L28 45.5359C24.2872 47.6795 19.7128 47.6795 16 45.5359L6.34937 
			39.9641C2.63655 37.8205 0.349365 33.859 0.349365 29.5718V18.4282C0.349365 14.141 2.63655 10.1795 6.34937 8.0359L16 2.4641Z" fill="">
                                        </path>
                                        <path d="M16.25 2.89711C19.8081 0.842838 24.1919 0.842837 27.75 2.89711L37.4006 8.46891C40.9587 10.5232 43.1506 14.3196 43.1506 
			18.4282V29.5718C43.1506 33.6804 40.9587 37.4768 37.4006 39.5311L27.75 45.1029C24.1919 47.1572 19.8081 47.1572 16.25 45.1029L6.59937 
			39.5311C3.04125 37.4768 0.849365 33.6803 0.849365 29.5718V18.4282C0.849365 14.3196 3.04125 10.5232 6.59937 8.46891L16.25 2.89711Z" stroke="">
                                        </path>
                                    </svg>
                                    <div class="absolute leading-none start-2/4 top-2/4 -translate-y-2/4 -translate-x-2/4 rtl:translate-x-2/4">
                                        <i class="ki-filled ki-abstract-39 text-xl ps-px text-primary">
                                        </i>
                                    </div>
                                </div>
                                <span class="text-mono text-sm font-medium">
                                    Expert Contributor Badge
                                </span>
                            </div>
                            <div class="kt-btn kt-btn-sm kt-btn-icon bg-transparent text-muted-foreground/60 group-hover:text-primary">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="14.5">
                                    </rect>
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="6.5">
                                    </rect>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center justify-between flex-wrap group border border-border rounded-xl gap-2 px-3.5 py-2.5">
                            <div class="flex items-center flex-wrap gap-2.5">
                                <div class="relative size-[50px] shrink-0">
                                    <svg class="w-full h-full stroke-yellow-200 dark:stroke-yellow-950 fill-yellow-100 dark:fill-yellow-950/30" fill="none" height="48" viewBox="0 0 44 48" width="44" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 2.4641C19.7128 0.320509 24.2872 0.320508 28 2.4641L37.6506 8.0359C41.3634 10.1795 43.6506 14.141 43.6506 
			18.4282V29.5718C43.6506 33.859 41.3634 37.8205 37.6506 39.9641L28 45.5359C24.2872 47.6795 19.7128 47.6795 16 45.5359L6.34937 
			39.9641C2.63655 37.8205 0.349365 33.859 0.349365 29.5718V18.4282C0.349365 14.141 2.63655 10.1795 6.34937 8.0359L16 2.4641Z" fill="">
                                        </path>
                                        <path d="M16.25 2.89711C19.8081 0.842838 24.1919 0.842837 27.75 2.89711L37.4006 8.46891C40.9587 10.5232 43.1506 14.3196 43.1506 
			18.4282V29.5718C43.1506 33.6804 40.9587 37.4768 37.4006 39.5311L27.75 45.1029C24.1919 47.1572 19.8081 47.1572 16.25 45.1029L6.59937 
			39.5311C3.04125 37.4768 0.849365 33.6803 0.849365 29.5718V18.4282C0.849365 14.3196 3.04125 10.5232 6.59937 8.46891L16.25 2.89711Z" stroke="">
                                        </path>
                                    </svg>
                                    <div class="absolute leading-none start-2/4 top-2/4 -translate-y-2/4 -translate-x-2/4 rtl:translate-x-2/4">
                                        <i class="ki-filled ki-abstract-44 text-xl ps-px text-yellow-600">
                                        </i>
                                    </div>
                                </div>
                                <span class="text-mono text-sm font-medium">
                                    Innovation Trailblazer
                                </span>
                            </div>
                            <div class="kt-btn kt-btn-sm kt-btn-icon bg-transparent text-muted-foreground/60 group-hover:text-primary">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="14.5">
                                    </rect>
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="6.5">
                                    </rect>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center justify-between flex-wrap group border border-border rounded-xl gap-2 px-3.5 py-2.5">
                            <div class="flex items-center flex-wrap gap-2.5">
                                <div class="relative size-[50px] shrink-0">
                                    <svg class="w-full h-full stroke-green-200 dark:stroke-green-950 fill-green-100 dark:fill-green-950/30" fill="none" height="48" viewBox="0 0 44 48" width="44" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 2.4641C19.7128 0.320509 24.2872 0.320508 28 2.4641L37.6506 8.0359C41.3634 10.1795 43.6506 14.141 43.6506 
			18.4282V29.5718C43.6506 33.859 41.3634 37.8205 37.6506 39.9641L28 45.5359C24.2872 47.6795 19.7128 47.6795 16 45.5359L6.34937 
			39.9641C2.63655 37.8205 0.349365 33.859 0.349365 29.5718V18.4282C0.349365 14.141 2.63655 10.1795 6.34937 8.0359L16 2.4641Z" fill="">
                                        </path>
                                        <path d="M16.25 2.89711C19.8081 0.842838 24.1919 0.842837 27.75 2.89711L37.4006 8.46891C40.9587 10.5232 43.1506 14.3196 43.1506 
			18.4282V29.5718C43.1506 33.6804 40.9587 37.4768 37.4006 39.5311L27.75 45.1029C24.1919 47.1572 19.8081 47.1572 16.25 45.1029L6.59937 
			39.5311C3.04125 37.4768 0.849365 33.6803 0.849365 29.5718V18.4282C0.849365 14.3196 3.04125 10.5232 6.59937 8.46891L16.25 2.89711Z" stroke="">
                                        </path>
                                    </svg>
                                    <div class="absolute leading-none start-2/4 top-2/4 -translate-y-2/4 -translate-x-2/4 rtl:translate-x-2/4">
                                        <i class="ki-filled ki-abstract-25 text-xl ps-px text-green-600">
                                        </i>
                                    </div>
                                </div>
                                <span class="text-mono text-sm font-medium">
                                    Impact Recognition
                                </span>
                            </div>
                            <div class="kt-btn kt-btn-sm kt-btn-icon bg-transparent text-muted-foreground/60 group-hover:text-primary">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="14.5">
                                    </rect>
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="6.5">
                                    </rect>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center justify-between flex-wrap group border border-border rounded-xl gap-2 px-3.5 py-2.5">
                            <div class="flex items-center flex-wrap gap-2.5">
                                <div class="relative size-[50px] shrink-0">
                                    <svg class="w-full h-full stroke-violet-200 dark:stroke-violet-950 fill-violet-100 dark:fill-violet-950/30" fill="none" height="48" viewBox="0 0 44 48" width="44" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 2.4641C19.7128 0.320509 24.2872 0.320508 28 2.4641L37.6506 8.0359C41.3634 10.1795 43.6506 14.141 43.6506 
			18.4282V29.5718C43.6506 33.859 41.3634 37.8205 37.6506 39.9641L28 45.5359C24.2872 47.6795 19.7128 47.6795 16 45.5359L6.34937 
			39.9641C2.63655 37.8205 0.349365 33.859 0.349365 29.5718V18.4282C0.349365 14.141 2.63655 10.1795 6.34937 8.0359L16 2.4641Z" fill="">
                                        </path>
                                        <path d="M16.25 2.89711C19.8081 0.842838 24.1919 0.842837 27.75 2.89711L37.4006 8.46891C40.9587 10.5232 43.1506 14.3196 43.1506 
			18.4282V29.5718C43.1506 33.6804 40.9587 37.4768 37.4006 39.5311L27.75 45.1029C24.1919 47.1572 19.8081 47.1572 16.25 45.1029L6.59937 
			39.5311C3.04125 37.4768 0.849365 33.6803 0.849365 29.5718V18.4282C0.849365 14.3196 3.04125 10.5232 6.59937 8.46891L16.25 2.89711Z" stroke="">
                                        </path>
                                    </svg>
                                    <div class="absolute leading-none start-2/4 top-2/4 -translate-y-2/4 -translate-x-2/4 rtl:translate-x-2/4">
                                        <i class="ki-filled ki-delivery-24 text-xl ps-px text-violet-600">
                                        </i>
                                    </div>
                                </div>
                                <span class="text-mono text-sm font-medium">
                                    Performance Honor
                                </span>
                            </div>
                            <div class="kt-btn kt-btn-sm kt-btn-icon bg-transparent text-muted-foreground/60 group-hover:text-primary">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="14.5">
                                    </rect>
                                    <rect fill="currentColor" height="3" rx="1.5" width="18" x="3" y="6.5">
                                    </rect>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection