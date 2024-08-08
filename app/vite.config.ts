import react from '@vitejs/plugin-react'

import { resolve } from 'path'
import { defineConfig } from 'vite'
import symfonyPlugin from 'vite-plugin-symfony'

import { TanStackRouterVite } from '@tanstack/router-vite-plugin'

export default defineConfig({
  plugins: [
    react(),
    symfonyPlugin(),
    TanStackRouterVite({
      routesDirectory: './assets/routes',
      generatedRouteTree: './assets/routeTree.gen.ts',
    }),
  ],
  build: {
    rollupOptions: {
      input: {
        app: './assets/index.tsx',
      },
    },
  },
  resolve: {
    alias: [
      { find: '~/app', replacement: resolve(__dirname, './assets') },
      { find: '@', replacement: resolve(__dirname, './assets') },
    ],
  },
  server: {
    watch: {
      usePolling: true,
    },
    host: true,
    port: 5173,
    hmr: {
      protocol: 'ws',
      host: 'localhost',
      port: 5173,
      clientPort: 5173,
    },
  },
})
