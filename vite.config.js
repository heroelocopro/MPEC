import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0",
        hmr: {
            host: "localhost",
            protocol: "ws",
        },
        cors: true,
    },
    build: {
        manifest: true,
        outDir: "public/build",
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`,
            },
        },
    },
    optimizeDeps: {
        include: ["laravel-vite-plugin"],
    },
    resolve: {
        alias: {
            // Asegúrate de que las rutas a los paquetes de vendor sean correctas
            "../../vendor": "/var/www/html/vendor",
        },
    },
});
