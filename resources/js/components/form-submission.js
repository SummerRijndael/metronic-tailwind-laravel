// ====================================================================\
// 0. UTILITY: TOAST NOTIFICATION HANDLER
// ====================================================================\
/**
 * Displays a toast notification using KTToast (assumed available from Metronic).
 * It uses the recommended static KTToast.show() method.
 * @param {string} message - The message to display.
 * @param {string} type - 'success', 'error, or 'warning'.
 */
const showToast = (message, type = "info") => {
    if (typeof KTToast !== "undefined" && typeof KTToast.show === "function") {
        let variant = "info";
        let icon = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>`; // Default info icon

        switch (type) {
            case "success":
                variant = "success";
                icon = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>`;
                break;
            case "error":
                variant = "destructive";
                icon = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>`;
                break;
            case "warning":
                variant = "warning";
                icon = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-9-15.59a1.99 1.99 0 0 0-3.46 0l-9 15.59A1.99 1.99 0 0 0 3 21h18a1.99 1.99 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>`;
                break;
            case "info":
            default:
                variant = "info";
                break;
        }

        try {
            // Toast position is set to 'top-end' (top-right)
            console.log("Showing toast:", message, type);
            KTToast.show({
                title: type.charAt(0).toUpperCase() + type.slice(1),
                message: message,
                position: "top-end",
                appearance: "solid",
                variant: variant,
                customIcon: icon,
            });
        } catch (e) {
            // Fallback for errors thrown by KTToast.show
            console.error("KTToast.show failed unexpectedly:", e);
            console.log(
                `[TOAST | ${type.toUpperCase()}] ${message} (Using console fallback)`,
            );
        }
    } else {
        // Fallback for environment without KTToast
        console.log(`[TOAST | ${type.toUpperCase()}] ${message}`);
    }
};

// ====================================================================\
// 1. UTILITY: THROTTLING
// ====================================================================\
/**
 * Throttles a function call.
 * @param {Function} func - The function to throttle.
 * @param {number} limit - The time in milliseconds to wait before allowing another call.
 * @returns {Function} - The throttled function.
 */
const throttle = (func, limit) => {
    let lastFunc;
    let lastRan;
    return function () {
        const context = this;
        const args = arguments;
        if (!lastRan) {
            func.apply(context, args);
            lastRan = Date.now();
        } else {
            clearTimeout(lastFunc);
            lastFunc = setTimeout(
                function () {
                    if (Date.now() - lastRan >= limit) {
                        func.apply(context, args);
                        lastRan = Date.now();
                    }
                },
                limit - (Date.now() - lastRan),
            );
        }
    };
};

// ====================================================================\
// 2. UTILITY: ERROR HANDLING AND RENDERING
// ====================================================================\

/**
 * Renders validation error messages from a server response into the DOM,
 * applying styling and ARIA attributes for accessibility.
 * @param {Object} errors - An object where keys are field names and values are arrays of error messages.
 */
const renderValidationErrors = (errors) => {
    // 1. Clear all previous custom error messages and ARIA attributes
    document.querySelectorAll('[id^="error-"]').forEach((el) => {
        el.textContent = "";

        // Extract the field name from the error container's ID (e.g., "error-bday" -> "bday")
        const fieldName = el.id.replace("error-", "");
        // Find the corresponding input element using its name attribute
        const inputElement = document.querySelector(`[name="${fieldName}"]`);

        if (inputElement) {
            // Remove error styling and ARIA attributes
            inputElement.classList.remove("border-destructive");
            inputElement.removeAttribute("aria-invalid");
            inputElement.removeAttribute("aria-describedby");
        }
    });

    // 2. Render new errors and apply ARIA/styling
    for (const field in errors) {
        if (errors.hasOwnProperty(field)) {
            const errorMessages = errors[field];
            const errorContainer = document.getElementById(`error-${field}`);
            // Find the input/textarea/select element using its name
            const inputElement = document.querySelector(`[name="${field}"]`);

            // Use the first error message for display
            if (errorContainer && errorMessages.length > 0) {
                errorContainer.textContent = errorMessages[0];

                if (inputElement) {
                    // Apply error class
                    inputElement.classList.add("border-destructive");

                    // Apply ARIA attributes for accessibility
                    inputElement.setAttribute("aria-invalid", "true");
                    inputElement.setAttribute(
                        "aria-describedby",
                        errorContainer.id,
                    );
                }
            }
        }
    }

    // Scroll to the first element marked as invalid for better UX
    const firstError = document.querySelector('[aria-invalid="true"]');
    if (firstError) {
        firstError.scrollIntoView({ behavior: "smooth", block: "center" });
    }
};

// ====================================================================\
// 3. CORE: FORM SUBMISSION LOGIC (Wrapped in DOMContentLoaded to prevent timing issues)
// ====================================================================\

document.addEventListener("DOMContentLoaded", function () {
    // Target all forms with the data-ajax-form="true" attribute for AJAX submission
    document.querySelectorAll('[data-ajax-form="true"]').forEach((form) => {
        // Get the loading button associated with this form
        const loadingButton = form.querySelector(
            '[data-loading-button="true"]',
        );

        // IMPORTANT: By being inside DOMContentLoaded, we guarantee that
        // loading-button.js has already run setupLoadingButton on this element.
        const toggleLoadingState = loadingButton
            ? loadingButton.toggleLoadingState
            : (isLoading) => console.log("Loading state: " + isLoading);

        // --- Core Submission Logic ---
        const submitHandler = async () => {
            const url = form.getAttribute("action");
            // Use method attribute if present, otherwise default to POST
            const method = form.getAttribute("method") || "POST";
            const formData = new FormData(form);

            // Ensure proper method handling for Laravel/AJAX (PATCH/PUT via _method)
            if (
                method.toUpperCase() === "PATCH" ||
                method.toUpperCase() === "PUT"
            ) {
                formData.append("_method", method.toUpperCase());
            }

            // Headers required to signal to the server that this is an AJAX request
            const headers = {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            };

            try {
                // Clear all previous errors before submission
                renderValidationErrors({});

                const response = await fetch(url, {
                    method: "POST", // Must be POST when using FormData/multipart
                    headers: headers, // Added AJAX headers
                    body: formData,
                });

                // Attempt to parse JSON response for details, even on errors
                let responseData = null;
                try {
                    // Check content type for JSON
                    const contentType = response.headers.get("content-type");
                    if (
                        contentType &&
                        contentType.includes("application/json")
                    ) {
                        responseData = await response.json();
                    }
                } catch (e) {
                    // Ignore parsing errors if response is not JSON
                    console.warn("Response was not valid JSON.", e);
                }

                // --- Find and prepare file-related inputs for cleanup (Assuming profile update context) ---
                const fileInput = form.querySelector(
                    'input[type="file"][name="avatar"]',
                );
                const removeInput = form.querySelector(
                    'input[type="hidden"][name="avatar_remove"]',
                );
                const wrapper = form.querySelector(".kt-image-input-wrapper");

                // --- Handle Response Status ---
                if (response.ok) {
                    // CRITICAL CLEANUP: Reset file inputs and removal flags after successful submission
                    if (fileInput) fileInput.value = "";
                    if (removeInput) removeInput.value = "";

                    if (responseData) {
                        // Success with JSON data (200, 201)
                        // **SUCCESS MESSAGE & TYPE FROM SERVER**
                        const toastMessage =
                            responseData.message ||
                            "Changes saved successfully!";
                        const toastType = responseData.type || "success";
                        console.log("Showing toast:", toastMessage, toastType);
                        showToast(toastMessage, toastType);

                        // OPTIONAL: Update image preview if a new URL is returned and elements exist
                        if (responseData.avatar_url && wrapper) {
                            // Use the new URL to update the background image
                            wrapper.style.backgroundImage = `url('${responseData.avatar_url}')`;
                        }
                    } else if (response.status === 204) {
                        // Success with No Content (204)
                        showToast("Changes saved successfully!", "success");
                    } else {
                        // Returns 200/201 without JSON
                        showToast(
                            "Changes saved, but server response was unexpected.",
                            "success",
                        );
                    }
                } else if (response.status === 422 && responseData) {
                    // Validation Errors
                    showToast("Please check the form for errors.", "error"); // Show a general error message
                    renderValidationErrors(responseData.errors);
                } else if (response.status === 419) {
                    // CSRF Token Mismatch
                    showToast(
                        "Session expired. Please reload the page and try again.",
                        "error",
                    );
                } else {
                    // General HTTP error (500, 403, etc.)
                    const message =
                        responseData?.message ||
                        `Server responded with status ${response.status}.`;

                    // **ERROR MESSAGE & TYPE FROM SERVER**
                    const type = responseData?.type || "error";

                    showToast(message, type);
                }
            } catch (error) {
                showToast(
                    "A network error occurred. Please check your connection.",
                    "error",
                );
            } finally {
                toggleLoadingState(false);
            }
        };

        // --- Throttled Submission Logic with Loading State ---
        const throttledSubmit = throttle(submitHandler, 2000);

        form.addEventListener("submit", (e) => {
            e.preventDefault(); // Always prevent default form submission immediately
            toggleLoadingState(true); // Turn on loading state immediately
            throttledSubmit();
        });
    });
});
