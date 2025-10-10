<div class="kt-card pb-2.5">
    <form id="profile-update-form" action="{{ route('profile.update', Auth::user()->id) }}" method="POST">
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
                        <div id="avatar-input-component" class="kt-image-input size-16" data-kt-image-input="true"
                            data-kt-image-input-initialized="true">
                            <input accept=".png, .jpg, .jpeg" name="avatar" type="file">
                            <input name="avatar_remove" type="hidden">
                            <button class="kt-image-input-remove" data-kt-image-input-remove="true"
                                data-kt-tooltip="true" data-kt-tooltip-placement="right" data-kt-tooltip-trigger="hover"
                                type="button" data-kt-tooltip-initialized="true">
                                <i class="ki-filled ki-cross"></i>
                                <span class="kt-tooltip" data-kt-tooltip-content="true">
                                    Click to remove or revert
                                </span>
                            </button>
                            <div class="kt-image-input-placeholder kt-image-input-empty:border-input border-2 border-green-500"
                                data-kt-image-input-placeholder="true"
                                style="background-image:url({{ asset('assets/media/avatars/blank.png') }})">
                                <div class="kt-image-input-preview" data-kt-image-input-preview="true"
                                    style="background-image:url('{{ $user->avatar_url }}')">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 right-0 flex h-5 cursor-pointer items-center justify-center bg-black/25">
                                    {{-- SVG for camera icon --}}
                                    <svg class="fill-border opacity-80" height="12" viewBox="0 0 14 12"
                                        width="14" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.6665 2.64585H11.2232C11.0873 2.64749 10.9538 2.61053 10.8382 2.53928C10.7225 2.46803 10.6295 2.36541 10.5698 2.24335L10.0448 1.19918C9.91266 0.931853 9.70808 0.707007 9.45438 0.550249C9.20068 0.393491 8.90806 0.311121 8.60984 0.312517H5.38984C5.09162 0.311121 4.799 0.393491 4.5453 0.550249C4.2916 0.707007 4.08701 0.931853 3.95484 1.19918L3.42984 2.24335C3.37021 2.36541 3.27716 2.46803 3.1615 2.53928C3.04584 2.61053 2.91234 2.64749 2.7765 2.64585H2.33317C1.90772 2.64585 1.49969 2.81486 1.19885 3.1157C0.898014 3.41654 0.729004 3.82457 0.729004 4.25002V10.0834C0.729004 10.5088 0.898014 10.9168 1.19885 11.2177C1.49969 11.5185 1.90772 11.6875 2.33317 11.6875H11.6665C12.092 11.6875 12.5 11.5185 12.8008 11.2177C13.1017 10.9168 13.2707 10.5088 13.2707 10.0834V4.25002C13.2707 3.82457 13.1017 3.41654 12.8008 3.1157C12.5 2.81486 12.092 2.64585 11.6665 2.64585ZM6.99984 9.64585C6.39413 9.64585 5.80203 9.46624 5.2984 9.12973C4.79478 8.79321 4.40225 8.31492 4.17046 7.75532C3.93866 7.19572 3.87802 6.57995 3.99618 5.98589C4.11435 5.39182 4.40602 4.84613 4.83432 4.41784C5.26262 3.98954 5.80831 3.69786 6.40237 3.5797C6.99644 3.46153 7.61221 3.52218 8.1718 3.75397C8.7314 3.98576 9.2097 4.37829 9.54621 4.88192C9.88272 5.38554 10.0623 5.97765 10.0623 6.58335C10.0608 7.3951 9.73765 8.17317 9.16365 8.74716C8.58965 9.32116 7.81159 9.64431 7 9.64585Z">
                                        </path>
                                        <path
                                            d="M7 8.77087C8.20812 8.77087 9.1875 7.7915 9.1875 6.58337C9.1875 5.37525 8.20812 4.39587 7 4.39587C5.79188 4.39587 4.8125 5.37525 4.8125 6.58337C4.8125 7.7915 5.79188 8.77087 7 8.77087Z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

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
                        value="{{ old('bday', $user->bday) }}"
                        class="kt-input @error('bday') border-red-500 @enderror" />
                </div>

                {{-- 2. DEDICATED JS ERROR MESSAGE CONTAINER --}}
                <div id="error-bday" class="text-destructive mt-1 text-sm">
                    {{-- The JS will inject the error here --}}
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-2.5">
                <button class="kt-btn kt-btn-primary">Save Changes</button>
            </div>

        </div>
    </form>
</div>

{{-- 3. THE UPDATED JAVASCRIPT IMPLEMENTATION --}}
@push('scripts')
    <script>
        // ====================================================================
        // 1. UTILITY: THROTTLING FUNCTION (FIXED)
        // **NEW:** Now executes e.preventDefault() on every call.
        // ====================================================================
        const throttle = (func, limit) => {
            let inThrottle;
            return function(e) { // Capture the event object

                // CRITICAL FIX: ALWAYS PREVENT DEFAULT BEHAVIOR
                if (e && e.preventDefault) {
                    e.preventDefault();
                }

                const args = arguments;
                const context = this;

                // Only proceed with the function (AJAX call) if not currently throttled
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            }
        }

        // ====================================================================
        // 2. HACK: KTIMAGEINPUT PROTOTYPE OVERRIDE
        // ====================================================================
        if (typeof KTImageInput !== 'undefined') {
            const KTI_PROTOTYPE = KTImageInput.prototype;
            const originalChange = KTI_PROTOTYPE._change;

            if (typeof originalChange === 'function') {
                KTI_PROTOTYPE._change = function() {
                    if (this._inputElement && this._inputElement.files.length > 0) {
                        this._hackedFileReference = this._inputElement.files[0];
                    }
                    originalChange.apply(this, arguments);
                };
                console.log("KTImageInput: Successfully hacked _change handler to prevent file loss.");
            }
        }

        // ====================================================================
        // 3. MAIN SCRIPT LOGIC
        // ====================================================================
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profile-update-form');
            const saveButton = form ? form.querySelector('.kt-btn-primary') : null;

            if (!form) {
                console.error('AJAX Script failed to find form with id="profile-update-form"');
                return;
            }

            // --- STATE VARIABLES ---
            let avatarRemoved = false;
            let imageInputInstance = null;
            const ktComponentEl = document.getElementById('avatar-input-component');

            if (typeof KTImageInput !== 'undefined' && ktComponentEl) {
                imageInputInstance = KTImageInput.getInstance(ktComponentEl);
            }
            // --- END STATE VARIABLES ---

            // --- AVATAR REMOVAL LISTENER ---
            const removeButton = form.querySelector('[data-kt-image-input-action="remove"]');
            if (removeButton) {
                removeButton.addEventListener('click', () => {
                    setTimeout(() => {
                        avatarRemoved = true;
                        if (imageInputInstance) {
                            imageInputInstance._hackedFileReference = null;
                        }
                        console.log("Avatar removal signaled.");
                    }, 10);
                });
            }
            // --- END AVATAR REMOVAL LISTENER ---


            // Helper function to clear all existing errors and styling attributes
            function clearValidationErrors() {
                document.querySelectorAll('[id^="error-"]').forEach(el => el.textContent = '');
                document.querySelectorAll('input, select, textarea').forEach(input => {
                    input.removeAttribute('aria-invalid');
                });
            }

            // Helper function to render validation errors
            function renderValidationErrors(errors) {
                clearValidationErrors();

                for (const fieldName in errors) {
                    if (!errors.hasOwnProperty(fieldName)) continue;

                    const errorElement = document.getElementById(`error-${fieldName}`);
                    if (errorElement) {
                        errorElement.textContent = errors[fieldName][0];
                    }

                    const input = form.querySelector(`[name="${fieldName}"]`);
                    if (input) {
                        input.setAttribute('aria-invalid', 'true');
                    }
                }
            }

            // The main submission logic
            const submitHandler = async function(e) {
                // NOTE: e.preventDefault() removed here, as it's now handled by the throttle wrapper.

                clearValidationErrors();

                if (saveButton) {
                    saveButton.setAttribute('data-kt-indicator', 'on');
                    saveButton.disabled = true;
                }

                const formData = new FormData(form);
                let selectedFile = null;

                // 1. Retrieve the file from the hack reference
                if (imageInputInstance) {
                    if (imageInputInstance._hackedFileReference && imageInputInstance
                        ._hackedFileReference instanceof File) {
                        selectedFile = imageInputInstance._hackedFileReference;
                    }
                }

                // 2. Prepare FormData based on state
                if (avatarRemoved) {
                    formData.set('avatar_remove', '1');
                    formData.delete('avatar');
                    console.log("Final action: AVATAR REMOVED.");
                } else if (selectedFile instanceof File) {
                    formData.set('avatar', selectedFile);
                    formData.delete('avatar_remove');
                    console.log(`Final action: NEW FILE attached: ${selectedFile.name}`);
                } else {
                    formData.delete('avatar');
                    formData.delete('avatar_remove');
                    console.log("Final action: NO AVATAR CHANGE.");
                }

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const responseData = await response.json();

                    if (response.ok) {
                        // SUCCESS (CLEANUP)
                        console.log('Profile Updated:', responseData.message);

                        if (imageInputInstance) {
                            imageInputInstance._hackedFileReference = null;
                        }
                        avatarRemoved = false;

                        // TODO: Implement your successful update UI logic (e.g., success toast)

                    } else if (response.status === 422) {
                        // VALIDATION ERROR
                        renderValidationErrors(responseData.errors);
                        console.error('Validation Failed:', responseData.message);

                    } else {
                        // SERVER ERROR
                        console.error('Server Error:', responseData.message);
                    }

                } catch (error) {
                    console.error('Fetch Error:', error);
                } finally {
                    if (saveButton) {
                        saveButton.removeAttribute('data-kt-indicator');
                        saveButton.disabled = false;
                    }
                }
            };

            // Attach the throttled submit handler to the form
            form.addEventListener('submit', throttle(submitHandler, 2000));
        });
    </script>
@endpush
