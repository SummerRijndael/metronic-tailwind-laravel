/** @type {import('tailwindcss').Config} */
module.exports = {
    // DEV NOTE: The 'content' array should be as restrictive as possible
    // to keep build times fast and the final CSS file size small.
    content: [
        // Standard Laravel/Blade paths (Optimized for most common usage)
        "./resources/views/**/*.blade.php",

        // Storage/Views should typically NOT be needed unless you dynamically compile views to storage.
        // DEV NOTE: REMOVED './storage/framework/views/*.php' - Rarely needed and slows down JIT/Watcher.

        // Vendor Pagination Views (Keep this for styling Laravel's built-in pagination component)
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",

        // DEV NOTE: Added specific paths for common JS/TS/Vue/React files if they exist in the root/resources/js directory.
        "./resources/js/**/*.{js,jsx,ts,tsx,vue}",

        // REMOVED: Placeholder paths like "./src/**/*.{html,js}", "./pages/**/*.{html,js,jsx}", "./components/**/*.{js,jsx}"
        // These are not typical in a standard Laravel project structure and can be confusing/redundant.
    ],

    // DEV NOTE: Keep 'class' for dark mode. This is the preferred method for toggling a theme.
    darkMode: "class",

    theme: {
        extend: {
            // DEV NOTE: Using 'defaultTheme' from Tailwind is the most robust way to extend fonts.
            // This ensures all fallbacks are correctly maintained.
            fontFamily: {
                sans: [
                    "Inter",
                    ...require("tailwindcss/defaultTheme").fontFamily.sans,
                ],
                serif: [
                    "Merriweather",
                    ...require("tailwindcss/defaultTheme").fontFamily.serif,
                ],
            },

            // DEV NOTE: This custom color setup using CSS variables is excellent for theming.
            colors: {
                // Primary/Accent Color Example (often needed)
                primary: "rgb(var(--tw-color-primary) / <alpha-value>)",

                background: "rgb(var(--tw-color-background) / <alpha-value>)",
                foreground: "rgb(var(--tw-color-foreground) / <alpha-value>)",
                "muted-foreground":
                    "rgb(var(--tw-color-muted-foreground) / <alpha-value>)",
                "secondary-foreground":
                    "rgb(var(--tw-color-secondary-foreground) / <alpha-value>)",
                border: "rgb(var(--tw-color-border) / <alpha-value>)",
                mono: "rgb(var(--tw-color-mono) / <alpha-value>)",
            },

            // DEV NOTE: Adding custom spacing is clean and correct.
            spacing: {
                5.5: "1.375rem",
                7.5: "1.875rem",
            },
        },
    },

    // DEV NOTE: Plugins are correctly registered here.
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("@tailwindcss/aspect-ratio"),
        // OPTIMIZATION: If you are using Tailwind 3.0+, consider adding the 'line-clamp' plugin if needed.
        // require('@tailwindcss/line-clamp'),
    ],

    // DEV NOTE: Safelist patterns are correct for preserving dynamically injected classes
    // (e.g., from Metronic themes or custom JS libs).
    safelist: [
        // Preserve all kt- prefixed classes
        {
            pattern: /^kt-/,
            // OPTIMIZATION: Restrict safelisting to certain utilities if possible (e.g., only colors, spacing)
            // variants: ['sm', 'md', 'lg', 'xl'],
        },
        // Preserve data attributes, often used for JavaScript-driven components
        {
            pattern: /^data-kt-/,
        },
    ],
};
