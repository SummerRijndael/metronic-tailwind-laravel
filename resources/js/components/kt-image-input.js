/**
 * Defensive Hack for Metronic's KTImageInput.
 *
 * This module ensures the required prototype modification is applied *only once*
 * and only *after* the KTImageInput class is available globally.
 *
 * The hack saves the selected File object before the Metronic component
 * clears the input element's files list (the core bug).
 */

// Global flag to ensure the prototype is only modified once.
let isPrototypeHacked = false;

// We will store a reference to the single instance we care about for retrieval later.
let imageInputInstance = null;

// The element ID defined in basic_info.blade.php
const IMAGE_INPUT_COMPONENT_ID = "avatar-input-component";

// ====================================================================
// 1. STATE EXPOSURE: A clean public interface for form-submission.js
// ====================================================================

/**
 * Provides the current state of the image input component for form submission.
 * This decouples the form logic from the internal hack details.
 * @returns {{file: File|null, isRemoved: boolean}}
 */
window.getAvatarFileState = () => {
    // If the instance isn't globally registered yet, try to find it on the element
    if (!imageInputInstance) {
        const el = document.getElementById(IMAGE_INPUT_COMPONENT_ID);
        if (el && el._ktImageInputInstance) {
            imageInputInstance = el._ktImageInputInstance;
        } else {
            // This is non-critical if the user hasn't interacted with the image yet
            console.warn(
                "KTImageInput instance not found. Interaction may be needed.",
            );
            return { file: null, isRemoved: false };
        }
    }

    // Default to false/null if properties don't exist yet
    const isRemoved = imageInputInstance.avatarRemoved || false;
    const file = imageInputInstance._hackedFileReference || null;

    return { file, isRemoved };
};

// ====================================================================
// 2. THE HACK APPLICATION LOGIC
// ====================================================================

/**
 * Applies the prototype override to the KTImageInput class.
 */
function applyKTImageInputHack() {
    // Check if the class exists and if we've already run the hack
    if (typeof KTImageInput === "undefined" || isPrototypeHacked) {
        return; // Exit if class not ready or hack already applied
    }

    try {
        const KTImageInputPrototype = KTImageInput.prototype;
        // Store the original function reference
        const originalChange = KTImageInputPrototype._change;

        // Override the _change function
        KTImageInputPrototype._change = function () {
            // 1. CRITICAL: Save the file reference from the input element
            this._hackedFileReference = this._inputElement.files.length
                ? this._inputElement.files[0]
                : null;

            // 2. Mark that a file was added/changed
            if (this._hackedFileReference) {
                this.avatarRemoved = false;
            }

            // 3. Register this instance globally if it's the one we care about
            if (this.element.id === IMAGE_INPUT_COMPONENT_ID) {
                imageInputInstance = this;
                // Also store it on the element itself for defensive retrieval
                this.element._ktImageInputInstance = this;
            }

            // 4. Call the original Metronic function to handle the UI update
            originalChange.apply(this, arguments);
        };

        // Override the _remove function to track removal state
        const originalRemove = KTImageInputPrototype._remove;
        KTImageInputPrototype._remove = function (e) {
            // Track the removal state
            this.avatarRemoved = true;
            this._hackedFileReference = null; // Clear the stored file reference

            // Call the original Metronic function
            originalRemove.apply(this, arguments);
        };

        // If we reached here, the hack was successful
        isPrototypeHacked = true;
        console.info("KTImageInput prototype hack successfully applied.");
    } catch (e) {
        console.error("Failed to apply KTImageInput hack:", e);
    }
}

// ====================================================================
// 3. DEFENSIVE CHECK AND EXECUTION
// ====================================================================

/**
 * Polling function to wait for KTImageInput to be defined before running hack.
 */
function checkAndApplyHack() {
    // Check 1: Does the class exist?
    if (typeof KTImageInput !== "undefined") {
        // Step 1: Apply the hack (must happen BEFORE the Metronic auto-init runs)
        applyKTImageInputHack();

        // We stop here. We rely on the Metronic vendor scripts to handle the instantiation.
    } else {
        // If not found, wait and try again.
        setTimeout(checkAndApplyHack, 50);
    }
}

// Start the check after the DOM is fully loaded.
document.addEventListener("DOMContentLoaded", checkAndApplyHack);
