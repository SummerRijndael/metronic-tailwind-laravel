<div class="kt-card pb-2.5">
    <form data-ajax-form="true" action="{{ route('profile.update', Auth::user()->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="kt-card-header" id="basic_settings">
            <h3 class="kt-card-title">Basic Settings</h3>
        </div>

        <div class="kt-card-content grid gap-5">

            {{-- Photo --}}
            <div class="flex flex-wrap items-center gap-2.5">

                {{-- 1. LABEL COLUMN (Max width ensures the input column can align) --}}
                <label class="kt-form-label max-w-56">
                    Photo
                </label>

                {{-- 2. INPUT AND ERROR COLUMN (Must have 'grow' to fill remaining space) --}}
                <div class="flex grow flex-col gap-2.5">

                    {{-- Inner Flex Container (Wraps the info text and the image input itself) --}}
                    <div class="flex flex-wrap items-center justify-between gap-2.5">

                        <span class="text-sm text-secondary-foreground">
                            300x300 px JPEG, PNG Image
                        </span>

                        {{-- Image Input Component --}}
                        <x-image-uploader name="avatar" id="imgupld" align="left" size="sm" :preview="Auth::user()->avatar != 'blank.png'
                            ? asset('storage/' . Auth::user()->avatar)
                            : asset('assets/media/avatars/blank.png')">
                            <span class="mt-1 text-[8px] font-normal text-gray-400">PNG, JPG</span>
                        </x-image-uploader>

                    </div>

                    {{-- ERROR MESSAGE (Placed inside the 'grow' column, below the component) --}}
                    {{-- 2. DEDICATED JS ERROR MESSAGE CONTAINER --}}
                    <div id="error-avatar" class="text-destructive mt-1 text-sm">
                        {{-- The JS will inject the error here --}}
                    </div>

                </div>
            </div>

            {{-- Name --}}
            <div class="w-full">
                <div class="flex flex-wrap items-baseline gap-2.5 lg:flex-nowrap">
                    <label class="kt-form-label flex max-w-56 items-center gap-1">Name<span
                            class="text-destructive text-sm">*</span></label>
                    <div class="grow">
                        <input class="kt-input" type="text" name="name" value="{{ old('name', $user->name) }}"
                            placeholder="Enter name" required @error('name') aria-invalid="true" @enderror>

                        {{-- 2. DEDICATED JS ERROR MESSAGE CONTAINER --}}
                        <div id="error-name" class="text-destructive mt-1 text-sm">
                            {{-- The JS will inject the error here --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lastname --}}
            <div class="w-full">
                {{-- Main row for label and input container --}}
                <div class="flex flex-wrap items-baseline gap-2.5 lg:flex-nowrap">

                    {{-- 1. LABEL COLUMN --}}
                    <label class="kt-form-label flex max-w-56 items-center gap-1">Lastname</label>

                    {{-- 2. INPUT AND ERROR MESSAGE COLUMN (Must grow to fill space) --}}
                    <div class="grow">
                        {{-- Input Field --}}
                        <input class="kt-input w-full" type="text" name="lastname"
                            value="{{ old('lastname', $user->lastname) }}" placeholder="Enter lastname"
                            @error('lastname') aria-invalid="true" @enderror>

                        {{-- Error Message (Placed directly below the input) --}}
                        {{-- 2. DEDICATED JS ERROR MESSAGE CONTAINER --}}
                        <div id="error-lastname" class="text-destructive mt-1 text-sm">
                            {{-- The JS will inject the error here --}}
                        </div>
                    </div>
                </div>
            </div>



            {{-- Mobile --}}
            <div class="w-full">
                <div class="flex flex-wrap items-baseline gap-2.5 lg:flex-nowrap">
                    <label class="kt-form-label flex max-w-56 items-center gap-1">Phone number</label>

                    <div class="grow">
                        <input class="kt-input" type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                            placeholder="e.g. 09XXXXXXXXX or 639XXXXXXXXX"
                            @error('mobile') aria-invalid="true" @enderror>

                        {{-- 2. DEDICATED JS ERROR MESSAGE CONTAINER --}}
                        <div id="error-mobile" class="text-destructive mt-1 text-sm">
                            {{-- The JS will inject the error here --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gender --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <label class="kt-form-label max-w-56">Gender</label>
                <div class="grow">
                    <select class="kt-select" name="sex" data-kt-select="true"
                        data-kt-select-placeholder="Select an option"
                        data-kt-select-config='{"optionsClass": "kt-scrollable overflow-auto max-h-[250px]"}'>
                        <option value="">Select</option>
                        <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female
                        </option>
                    </select>
                </div>
                {{-- 2. DEDICATED JS ERROR MESSAGE CONTAINER --}}
                <div id="error-sex" class="text-destructive mt-1 text-sm">
                    {{-- The JS will inject the error here --}}
                </div>
            </div>

            {{-- Birthday --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <label class="kt-form-label max-w-56">Birthday</label>
                <div class="grow">
                    <x-date-picker name="bday" placeholder="Select your birthday"
                        value="{{ old('bday', $user->bday) }}" class="kt-input" />
                </div>

                {{-- 2. DEDICATED JS ERROR MESSAGE CONTAINER --}}
                <div id="error-bday" class="text-destructive mt-1 text-sm">
                    {{-- The JS will inject the error here --}}
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-2.5">
                <button data-loading-button="true" class="kt-btn kt-btn-primary">Save Changes</button>

            </div>

        </div>
    </form>
</div>
