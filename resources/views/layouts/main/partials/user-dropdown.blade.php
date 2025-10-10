<div data-kt-dropdown="true" data-kt-dropdown-offset="10px, 10px" data-kt-dropdown-offset-rtl="-20px, 10px"
    data-kt-dropdown-placement="bottom-start" data-kt-dropdown-placement-rtl="bottom-end" data-kt-dropdown-trigger="click">
    <div class="shrink-0 cursor-pointer" data-kt-dropdown-toggle="true">
        <img alt="" class="size-9 shrink-0 cursor-pointer rounded-full border-2 border-mono/25"
            src="{{ !Auth::user()->avatar == 'blank.png' ? asset('storage/' . Auth::user()->avatar) : asset('assets/media/avatars/blank.png') }}" />
    </div>
    <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
        <div class="flex items-center justify-between gap-1.5 px-2.5 py-1.5">
            <div class="flex items-center gap-2">
                <img alt="" class="size-9 shrink-0 rounded-full border-2 border-green-500"
                    src="{{ !Auth::user()->avatar == 'blank.png' ? asset('storage/' . Auth::user()->avatar) : asset('assets/media/avatars/blank.png') }}" />
                <div class="flex flex-col gap-1.5">
                    <span class="text-sm font-semibold leading-none text-foreground">
                        {{ auth()->user()->name }}
                    </span>
                    <a class="hover:text-primary text-xs font-medium leading-none text-secondary-foreground"
                        href="#">
                        {{ auth()->user()->email }}
                    </a>
                </div>
            </div>
            <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">
                Pro
            </span>
        </div>
        <ul class="kt-dropdown-menu-sub">
            <li>
                <div class="kt-dropdown-menu-separator">
                </div>
            </li>
            <li>
                <a class="kt-dropdown-menu-link" href="#">
                    <i class="ki-filled ki-profile-circle">
                    </i>
                    My Profile
                </a>
            </li>

            <li>
                <div class="kt-dropdown-menu-separator">
                </div>
            </li>
        </ul>
        <div class="mb-2.5 flex flex-col gap-3.5 px-2.5 pt-1.5">
            <div class="flex items-center justify-between gap-2">
                <span class="flex items-center gap-2">
                    <i class="ki-filled ki-moon text-base text-muted-foreground">
                    </i>
                    <span class="text-2sm font-medium">
                        Dark Mode
                    </span>
                </span>
                <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true"
                    name="check" type="checkbox" value="1" />
            </div>
            <a class="kt-btn kt-btn-outline w-full justify-center" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Log out
            </a>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>

        </div>
    </div>
</div>
