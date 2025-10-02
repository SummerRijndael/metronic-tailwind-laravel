<div>
    <!-- Theme Mode -->
<script data-navigate-once>
    /**
     * Initializes the theme on page load based on:
     * 1. window.userThemeSetting (Loaded from DB/Backend)
     * 2. localStorage (User's last client-side interaction)
     * 3. Default theme
     */
    (function initTheme() {
        // Default theme if none is specified or found anywhere.
        const defaultThemeMode = 'light'; // light | dark | system
        let themeMode;

        if (document.documentElement) {
            // Priority 1: Check for server-provided setting (from DB)
            if (window.userThemeSetting) {
                // If the backend provides a theme, use it. This overrides all client-side preferences.
                themeMode = window.userThemeSetting;
            }
            // Priority 2: Fallback to local storage (user's last preference saved on this device)
            else if (localStorage.getItem('kt-theme')) {
                themeMode = localStorage.getItem('kt-theme');
            }
            // Priority 3: Fallback to the hardcoded default
            else {
                themeMode = defaultThemeMode;
            }

            // 4. Resolve 'system' mode to 'dark' or 'light'
            if (themeMode === 'system') {
                themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            
            // 5. Apply the resolved theme class
            document.documentElement.classList.remove('dark', 'light');
            document.documentElement.classList.add(themeMode);
        }
    })();

    /**
     * Toggles the theme between 'light' and 'dark', saves the preference, and applies the class.
     * * NOTE: This function only saves to localStorage. If you want a user's choice to be permanent
     * across devices, you must update the backend logic on a button click, which would then
     * update the database and the 'window.userThemeSetting' variable on the next page load.
     */
    window.toggleThemeMode = function() {
        if (!document.documentElement) return;

        // 1. Get current theme (default to 'light' if neither is present)
        const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        
        // 2. Determine the new theme
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        // 3. Update the class on the <html> tag immediately
        document.documentElement.classList.remove(currentTheme);
        document.documentElement.classList.add(newTheme);

        // 4. Save the new preference to localStorage (optional, but good for quick reloads)
        localStorage.setItem('kt-theme', newTheme);
        
        console.log(`Theme toggled to: ${newTheme}. Remember to save this to your database!`);
    };
</script>
    <!-- End of Theme Mode -->
</div>
