// ---- PATCHED Livewire-safe app.js ----

import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

// --- Custom Components ---
import "./components/loading-button";
import "./components/form-submission";
import "./components/custom-image-uploader";

// --- Helper: Safe Init (no double binding) ---
function safeInit(callback, name) {
    try {
        callback();
        // console.debug(`[MetronicCore] Initialized: ${name}`);
    } catch (e) {
        console.warn(`[MetronicCore] ${name} failed:`, e);
    }
}

// --- DOM Ready ---
document.addEventListener("DOMContentLoaded", function () {
    // Initialize once at full load
    flatpickr.defaultConfig.dateFormat = "Y-m-d";
    document
        .querySelectorAll(".kt-datepicker")
        .forEach((el) => flatpickr(el, { allowInput: true }));

    safeInit(initDrawers, "Drawers");
    safeInit(initKTMenu, "KTMenu");
    safeInit(initStickyHeaders, "StickyHeaders");
    safeInit(initModals, "Modals");
});

// --- Metronic Core Hooks ---
function initKTMenu() {
    // Only run if Metronic’s KTMenu exists
    if (window.KTMenu?.init) {
        window.KTMenu.init();
    }
}

function initDrawers() {
    if (window.KTDrawer?.init) {
        window.KTDrawer.init();
    }
}

function initStickyHeaders() {
    if (window.KTSticky?.init) {
        window.KTSticky.init();
    }
}

function initModals() {
    if (window.KTModal?.init) {
        window.KTModal.init();
    }
}

// --- Livewire integration ---
document.addEventListener("livewire:init", () => {
    Livewire.hook("morph.updated", ({ component, el }) => {
        // only re-init elements *inside* this Livewire component
        if (el.closest("[data-livewire]")) {
            safeInit(initKTMenu, "KTMenu (Livewire)");
            safeInit(initDrawers, "Drawers (Livewire)");
            safeInit(initStickyHeaders, "StickyHeaders (Livewire)");
            safeInit(initModals, "Modals (Livewire)");
        }

        // flatpickr cleanup & re-init
        document.querySelectorAll(".kt-datepicker").forEach((el) => {
            if (!el._flatpickr) flatpickr(el, { allowInput: true });
        });
    });
});

// --- Export for custom calls ---
window.MetronicCore = {
    initDrawers,
    initKTMenu,
    initStickyHeaders,
    initModals,
};
