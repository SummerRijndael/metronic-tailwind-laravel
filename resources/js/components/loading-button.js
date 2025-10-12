/**
 * Reusable Loading Button Component Logic
 * Attaches loading state management, animation CSS, and rate limiting to buttons
 * with the attribute: data-loading-button="true".
 */

// ====================================================================\
// B. JS LOGIC
// ====================================================================\

const LOADING_HTML = `
    <div class="kt-loader-content">
        Saving
        <span class="kt-loader-dot"></span>
        <span class="kt-loader-dot"></span>
        <span class="kt-loader-dot"></span>
    </div>
`;

/**
 * Initializes and toggles the loading state for a single button element.
 * @param {HTMLElement} button - The button element to manage.
 */
function setupLoadingButton(button) {
    // Prevent double-setup
    if (button.hasAttribute("data-loading-initialized")) {
        return;
    }
    button.setAttribute("data-loading-initialized", "true");

    let originalButtonTextElement = null;

    // --- Setup: Wrap raw text content in a span ---
    const setupButtonText = () => {
        let saveButtonText = button.querySelector(".kt-btn-text");
        if (!saveButtonText) {
            const originalContent = button.innerHTML.trim();
            // Wrap the raw text content in the required span
            button.innerHTML = `<span class="kt-btn-text">${originalContent}</span>`;
            saveButtonText = button.querySelector(".kt-btn-text");
        }
        originalButtonTextElement = saveButtonText;
    };
    setupButtonText();

    /**
     * Toggles the loading state (applies the guaranteed size-lock and DOM swap).
     * This function is attached directly to the button element.
     * @param {boolean} isLoading
     */
    button.toggleLoadingState = (isLoading) => {
        // Only run if the button has the custom attribute set
        if (!button.hasAttribute("data-loading-button")) {
            return;
        }

        if (isLoading) {
            // 1. Lock dimensions (necessary to prevent reflow when content changes)
            button.style.width = `${button.offsetWidth}px`;
            button.style.height = `${button.offsetHeight}px`;

            // 2. Add loading class (for styling like opacity/position:relative)
            button.classList.add("kt-btn-loading");
            button.removeAttribute("data-kt-indicator");

            // 3. REMOVE original text from DOM
            if (
                originalButtonTextElement &&
                originalButtonTextElement.parentNode === button
            ) {
                originalButtonTextElement.remove();
            }

            // 4. Append the custom loading indicator
            if (!button.querySelector(".kt-loader-content")) {
                button.insertAdjacentHTML("beforeend", LOADING_HTML);
            }
        } else {
            // 1. Remove loading class
            button.classList.remove("kt-btn-loading");

            // 2. Clear locked dimensions
            button.style.width = "";
            button.style.height = "";

            // 3. RE-INSERT original text to DOM
            if (
                originalButtonTextElement &&
                originalButtonTextElement.parentNode !== button
            ) {
                button.prepend(originalButtonTextElement);
            }

            // 4. Remove custom loading indicator
            const loader = button.querySelector(".kt-loader-content");
            if (loader) {
                loader.remove();
            }
        }
    };
}

// Auto-initialize all buttons marked for loading when the DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll('[data-loading-button="true"]')
        .forEach(setupLoadingButton);
});

// CRITICAL: Expose the setup function globally so form-submission.js can
// explicitly ensure the button is set up before throttling.
window.setupLoadingButton = setupLoadingButton;
