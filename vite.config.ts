import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import {defineConfig} from 'vite';

export default defineConfig(() => {
  return {
    plugins: [
      laravel({
        input: ['resources/css/app.css', 'resources/js/app.js'],
        refresh: true,
      }),
      tailwindcss(),
    ],
    server: {
      hmr: process.env.DISABLE_HMR !== 'true',
      watch: process.env.DISABLE_HMR === 'true' ? null : {},
    },
  };
});
