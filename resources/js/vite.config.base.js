import { defineConfig } from 'vite';
import { pathAliases } from './config/resolve.js';
import { modulePageMapping } from './config/moduleMapping.js';
import path from 'path';

export function createViteConfig() {
    return defineConfig({
        resolve: {
            alias: Object.fromEntries(
                Object.entries(pathAliases).map(([key, value]) => [
                    key,
                    path.resolve(__dirname, value)
                ])
            ),
            extensions: ['.js', '.ts', '.vue', '.json'],
        },
        build: {
            minify: 'esbuild',
            sourcemap: false,
            chunkSizeWarningLimit: 1600,
            rollupOptions: {
                output: {
                    manualChunks(id) {
                        if (id.includes('node_modules')) {
                            return 'vendor';
                        }
                    },
                },
            },
        },
    });
}

// Export module mapping for Inertia
export { modulePageMapping };
