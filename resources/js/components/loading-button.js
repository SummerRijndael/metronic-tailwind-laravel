/**
 * Reusable Loading Button Component Logic
 */

// ====================================================================\
// B. JS LOGIC (Optimized)
// ====================================================================\

const LOADING_HTML = `
    <div class="kt-loader-content">
        Saving
        <span class="kt-loader-dot"></span>
        <span class="kt-loader-dot"></span>
        <span class="kt-loader-dot"></span>
    </div>
`;
const SELECTOR = '[data-loading-button="true"]:not([data-loading-initialized])';

/**
 * Initializes and toggles the loading state for a single button element.
 * @param {HTMLElement} button - The button element to manage.
 */
function setupLoadingButton(button) {
    // Check again in case another observer or event beat this one.
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
     * Toggles the loading state (attached directly to the button element).
     * @param {boolean} isLoading
     */
    button.toggleLoadingState = (isLoading) => {
        if (!button.hasAttribute("data-loading-button")) {
            return;
        }

        // ... (rest of your toggleLoadingState logic remains the same) ...
        if (isLoading) {
            // 1. Lock dimensions
            button.style.width = `${button.offsetWidth}px`;
            button.style.height = `${button.offsetHeight}px`;

            // 2. Add loading class
            button.classList.add("kt-btn-loading");
            button.removeAttribute("data-kt-indicator");

            // 💡 NEW DEBUGGING LOG
            console.log(
                "Attempting to remove element:",
                originalButtonTextElement,
            );

            // 3. REMOVE original text from DOM
            if (
                originalButtonTextElement &&
                originalButtonTextElement.parentNode === button
            ) {
                originalButtonTextElement.remove();
                console.log("Original element removed successfully.");
            } else {
                console.warn(
                    "Could not remove original element. Parent mismatch or element is null.",
                );
            }

            // 4. Append the custom loading indicator
            if (!button.querySelector(".kt-loader-content")) {
                button.insertAdjacentHTML("beforeend", LOADING_HTML);
                console.log("Loading HTML inserted.");
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

/**
 * Scan the DOM for uninitialized loading buttons and set them up.
 */
const initLoadingButtons = (root = document) => {
    root.querySelectorAll(SELECTOR).forEach(setupLoadingButton);
};

// --- Initialization Logic (Handles both static and dynamic elements) ---

// 1. Initialize all buttons present when the DOM is ready (or immediately if it already is)
// Corrected Line: Pass a function that explicitly calls initLoadingButtons(document)
document.addEventListener("DOMContentLoaded", function () {
    initLoadingButtons(document);
});

// 2. Use MutationObserver to initialize buttons added dynamically later (e.g., in a datatable redraw)
const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
        if (mutation.type === "childList") {
            // Check for new nodes that match the selector
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType === 1) {
                    // Check if it's an element
                    if (node.matches(SELECTOR)) {
                        setupLoadingButton(node);
                    }
                    // Also check for matching descendants
                    if (node.querySelector) {
                        node.querySelectorAll(SELECTOR).forEach(
                            setupLoadingButton,
                        );
                    }
                }
            });
        }
    });
});

// Start observing the document body for changes in the DOM tree
observer.observe(document.body, { childList: true, subtree: true });

// CRITICAL: Expose the setup function globally for external scripts to use as fallback/alternative
window.setupLoadingButton = setupLoadingButton;
