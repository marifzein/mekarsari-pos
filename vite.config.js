import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],

    // --- TAMBAHKAN BAGIAN INI ---
    server: {
        host: "0.0.0.0", // Biar server Vite bisa diakses dari IP lokal (HP)
        hmr: {
            host: "localhost", // Hot Module Replacement tetap ke localhost
        },
    },
    // ----------------------------
});
