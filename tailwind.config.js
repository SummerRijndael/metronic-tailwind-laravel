/** @type {import('tailwindcss').Config} */
module.exports = {
  // The 'content' array is the most important part.
  // It tells Tailwind where to find your HTML, JavaScript, and other files
  // that contain Tailwind classes. This is how Tailwind knows which classes to
  // keep and which to remove (purge) for the final build.
  content: [
    "./src/**/*.{html,js}",
    "./pages/**/*.{html,js,jsx}",
    "./components/**/*.{js,jsx}",
    // These paths are specific to Laravel and ensure Tailwind
    // correctly processes your Blade templates.
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],
  // This is a crucial setting that enables dark mode based on the presence
  // of the 'dark' class on the HTML element.
  darkMode: 'class',
  // The 'theme' object is where you customize your design system.
  // You can extend the default colors, fonts, spacing, and more.
  // Everything you define here will be available as a Tailwind class.
  theme: {
    // The 'extend' key allows you to add new values without
    // replacing Tailwind's defaults.
    extend: {
      // Define a custom font family.
      fontFamily: {
        sans: ['Inter', 'sans-serif'], // Sets 'Inter' as the default sans-serif font
        serif: ['Merriweather', 'serif'],
      },
      // Here is the missing custom color palette. It uses CSS variables
      // to allow for dynamic theming (e.g., light vs. dark mode).
      colors: {
        'background': 'rgb(var(--tw-color-background) / <alpha-value>)',
        'foreground': 'rgb(var(--tw-color-foreground) / <alpha-value>)',
        'muted-foreground': 'rgb(var(--tw-color-muted-foreground) / <alpha-value>)',
        'secondary-foreground': 'rgb(var(--tw-color-secondary-foreground) / <alpha-value>)',
        'border': 'rgb(var(--tw-color-border) / <alpha-value>)',
        'mono': 'rgb(var(--tw-color-mono) / <alpha-value>)',
      },
      // This section adds custom spacing values to your Tailwind scale.
      spacing: {
        '5.5': '1.375rem',
        '7.5': '1.875rem',
      }
    },
  },
  // The 'plugins' array is where you add any official or community plugins
  // to extend Tailwind's functionality.
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
  // The 'safelist' array tells Tailwind to preserve specific classes that it might
  // not be able to detect in the content files. This is essential for classes
  // added dynamically by JavaScript or a pre-built theme.
  safelist: [
    // Preserve all kt- prefixed classes
    {
      pattern: /^kt-/,
    },
    // Preserve data attributes, which are also often used for dynamic behavior
    {
      pattern: /^data-kt-/,
    },
  ]
}
