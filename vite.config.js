import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import path from "path";
import fs from "fs";

export default defineConfig(({ mode }) => {
    // Detect if running nested inside a Bagisto installation (e.g., packages/*/*)
    const isInsideBagisto = fs.existsSync(path.resolve(__dirname, "../../../public"));
    const publicDirectory = isInsideBagisto ? "../../../public" : "publishable";
    const hotFile = isInsideBagisto
        ? "../../../public/shop-beauty-vite.hot"
        : "publishable/shop-beauty-vite.hot";
    const buildDirectory = "themes/shop/beauty/build";

    return {
        build: {
            emptyOutDir: true,
            outDir: isInsideBagisto 
                ? path.resolve(__dirname, "../../../public", buildDirectory)
                : path.resolve(__dirname, "publishable/build"),
            minify: "esbuild",
            cssCodeSplit: true,
            rollupOptions: {
                output: {
                    manualChunks: {
                        vue: ["vue"],
                        veeValidate: ["vee-validate", "@vee-validate/rules", "@vee-validate/i18n"],
                        vendor: ["axios", "mitt"],
                    },
                },
            },
        },

        server: {
            host: process.env.VITE_HOST || "localhost",
            port: process.env.VITE_PORT || 5173,
            cors: true,
        },

        plugins: [
            vue(),

            tailwindcss(),

            laravel({
                hotFile: hotFile,
                publicDirectory: publicDirectory,
                buildDirectory: buildDirectory,
                input: [
                    "src/Resources/assets/css/app.css",
                    "src/Resources/assets/js/app.js",
                ],
                refresh: true,
            }),
        ],

        experimental: {
            renderBuiltUrl(filename, { hostType }) {
                if (hostType === "css") {
                    return path.basename(filename);
                }
            },
        },
    };
});
