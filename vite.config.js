import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import react from '@vitejs/plugin-react'
import { resolve } from 'path'

export default defineConfig({
    plugins: [
        react(),
        symfonyPlugin(),
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/index.jsx"
            },
        }
    },
    resolve: {
        alias: [{ find: "~/app", replacement: resolve(__dirname, "./assets") }]
    }
});
