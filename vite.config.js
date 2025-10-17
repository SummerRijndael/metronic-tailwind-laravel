import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    cacheDir: "node_modules/.vite", // Keep this good practice!
    server: {
        // Force Vite to listen on all interfaces in WSL
        host: "0.0.0.0",
        // Optional: specify port, but 5173 is standard
        port: 5173,
        hmr: {
            host: "localhost", // 👈 forces Vite to tell Laravel to use this
            protocol: "http",
            port: 5173,
        },
    },
    plugins: [
        laravel({
            host: "127.0.0.1",
            // CRITICAL: Set the dev server for the client refresh script
            dev_server: "127.0.0.1",
            // This sometimes helps with the auto-detection logic
            detectTls: false,
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            external: [
                // Correct regex to exclude Metronic assets
                /^assets\//,
            ],
        },
    },
});
