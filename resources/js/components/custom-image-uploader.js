/**
 * Class: ImageUploaderComponent
 * Handles a self-contained image upload and preview widget.
 */
class ImageUploaderComponent {
    /**
     * @param {HTMLElement} rootElement
     */
    constructor(rootElement) {
        this.root = rootElement;
        if (!this.root) {
            console.error("ImageUploaderComponent: Root element not found.");
            return;
        }

        // Element references
        this.fileInput = this.root.querySelector("input[type='file']");
        this.imagePreview = this.root.querySelector(
            '[data-element="preview-image"]',
        );
        this.placeholder = this.root.querySelector(
            '[data-element="placeholder"]',
        );
        this.previewContainer = this.root.querySelector(
            '[data-action="preview-container"]',
        );
        this.editButton = this.root.querySelector(
            '[data-action="edit-button"]',
        );
        // We now look up the error message element that is OUTSIDE the main avatar-upload div
        this.errorMessage = document.querySelector(
            `[data-element="error-message"]`,
        );

        // New reference for the element holding the initial background image style (The first child DIV of the preview container)
        this.initialPreviewHolder = this.previewContainer?.firstElementChild;

        // Capture initial preview URL provided via Blade (for restoring on removal)
        // We check the initialPreviewHolder's background-image style for reference.
        const initialStyle = this.initialPreviewHolder?.style.backgroundImage;
        this.initialPreview =
            initialStyle && initialStyle !== "none" ? initialStyle : null;

        // State FIX: isImageLoaded now means "user has actively uploaded a new image."
        // We start as false, even if a default image is present via `:preview`.
        this.isImageLoaded = false;

        // Bind handlers so we can remove them later
        this.handleImageUploadBound = this.handleImageUpload.bind(this);
        this.handleButtonClickBound = this.handleButtonClick.bind(this);
        this.handleContainerClickBound = this.handleContainerClick.bind(this);

        // Setup listeners
        this.initListeners();
        this.updateUI(); // Run once to set initial state (which is now the 'Pen' state)
    }

    /** Initialize all listeners */
    initListeners() {
        this.fileInput?.addEventListener("change", this.handleImageUploadBound);
        this.editButton?.addEventListener("click", this.handleButtonClickBound);
        this.previewContainer?.addEventListener(
            "click",
            this.handleContainerClickBound,
        );

        // Accessibility enhancement
        this.previewContainer?.setAttribute("role", "button");
        this.previewContainer?.setAttribute("tabindex", "0");
    }

    /** Helper to show an error message in the UI */
    showError(message) {
        if (this.errorMessage) {
            this.errorMessage.textContent = message;
            // Use CSS transition classes for visibility
            this.errorMessage.classList.remove("opacity-0", "h-0");
            this.errorMessage.classList.add("opacity-100", "h-auto");
        }
    }

    /** Helper to hide the error message */
    hideError() {
        if (this.errorMessage) {
            this.errorMessage.textContent = "";
            this.errorMessage.classList.remove("opacity-100", "h-auto");
            this.errorMessage.classList.add("opacity-0", "h-0");
        }
    }

    /** File selection handler */
    handleImageUpload(event) {
        this.hideError(); // Clear any previous error

        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith("image/")) {
                this.showError(
                    "Please select a valid image file (PNG, JPG, JPEG).",
                );
                event.target.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                // The uploaded file goes into the <img> tag
                if (this.imagePreview) this.imagePreview.src = e.target.result;

                // CRITICAL: A user file was selected, so the state is now TRUE
                this.isImageLoaded = true;

                this.updateUI();
            };
            reader.readAsDataURL(file);
        } else {
            // If file selection was cancelled, we don't change the state.
            this.updateUI();
        }
    }

    /** Click handler for edit/remove button */
    handleButtonClick(event) {
        // Only act as a remove button if a user-uploaded image is loaded
        if (this.isImageLoaded) {
            event.preventDefault(); // prevent file input click
            this.clearImage();
        }
        // If isImageLoaded is false, the label automatically triggers the file input.
    }

    /** Handles container click (upload trigger) */
    handleContainerClick() {
        if (!this.isImageLoaded && this.fileInput) {
            this.fileInput.click();
        }
    }

    /** Clears preview and restores initial state */
    clearImage() {
        // Clear the user-uploaded image from the img tag
        if (this.imagePreview) {
            this.imagePreview.src = "";
        }

        if (this.fileInput) this.fileInput.value = "";

        // CRITICAL: Reset state back to false (Pen icon state)
        this.isImageLoaded = false;

        this.updateUI();
        this.hideError();
    }

    /** Updates visual state (CSS-Driven) */
    updateUI() {
        if (
            !this.imagePreview ||
            !this.placeholder ||
            !this.editButton ||
            !this.initialPreviewHolder
        ) {
            console.warn("Missing UI elements for ImageUploaderComponent");
            return;
        }

        // --- CORE STATE MANAGEMENT (JS) ---
        if (this.isImageLoaded) {
            // STATE: User uploaded a file (Show NEW IMAGE + BIN icon)
            this.root.classList.add("is-uploaded");
            this.editButton.title = "Click to remove image";

            // 1. Show the IMG tag (which now holds the base64 user file)
            this.imagePreview.classList.remove("hidden");
            // 2. Hide the Blade background holder (prevents clash)
            this.initialPreviewHolder.classList.add("hidden");
            // 3. Hide the placeholder text
            this.placeholder.classList.add("hidden");
        } else {
            // STATE: No user file uploaded (Initial state / Cleared state - Show PEN icon)
            this.root.classList.remove("is-uploaded");
            this.editButton.title = "Click to upload new image";

            if (this.initialPreview) {
                // CASE 1: Initial preview URL was provided by Blade (blank.png etc.)

                // 1. Hide the IMG tag (it's empty or holds previous user upload)
                this.imagePreview.classList.add("hidden");
                // 2. Show the Blade background holder (shows the blank.png)
                this.initialPreviewHolder.classList.remove("hidden");
                // 3. Hide the placeholder text
                this.placeholder.classList.add("hidden");
            } else {
                // CASE 2: No initial preview provided (pure empty state)

                // 1. Hide the IMG tag
                this.imagePreview.classList.add("hidden");
                // 2. Hide the Blade background holder
                this.initialPreviewHolder.classList.add("hidden");
                // 3. Show the placeholder text
                this.placeholder.classList.remove("hidden");
            }
        }
    }

    /** Removes all listeners and clears references */
    destroy() {
        this.fileInput?.removeEventListener(
            "change",
            this.handleImageUploadBound,
        );
        this.editButton?.removeEventListener(
            "click",
            this.handleButtonClickBound,
        );
        this.previewContainer?.removeEventListener(
            "click",
            this.handleContainerClickBound,
        );
        this.root = null;
    }
}

/** Initialize all uploaders when DOM is ready */
window.addEventListener("DOMContentLoaded", () => {
    document
        .querySelectorAll('[data-image-uploader="true"]')
        .forEach((root) => {
            new ImageUploaderComponent(root);
        });
});
