import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    react(),
  ],
  server: {
    host: '0.0.0.0',
    port: 5173,
    strictPort: true,
    cors: {
      origin: 'http://localhost:8000',
      credentials: true,
    },
    hmr: {
      host: '172.17.251.157', // <- IP do WSL
      protocol: 'ws',
      port: 5173,
    },
    watch: {
      usePolling: true,
      interval: 100
    }
  },  
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
    },
  },
});