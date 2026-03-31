import { defineConfig } from 'vite';
import { modulePageMapping } from '../config/moduleMapping.js';
import path from 'path';

// Plugin to resolve Inertia pages to module components
export function moduleResolverPlugin() {
    return {
        name: 'module-resolver',
        resolveId(id) {
            // Check if the id matches a module page
            if (modulePageMapping[id]) {
                return modulePageMapping[id];
            }
            return null;
        },
        load(id) {
            // If this is a module page, create a proxy component
            const pageName = Object.keys(modulePageMapping).find(key => modulePageMapping[key] === id);
            if (pageName) {
                return `
                    <script setup>
                    import ModuleComponent from '${id}';
                    </script>
                    <template>
                        <ModuleComponent v-bind="$attrs" />
                    </template>
                `;
            }
            return null;
        }
    };
}

export function createModuleViteConfig(options = {}) {
    return defineConfig({
        plugins: [
            moduleResolverPlugin(),
            ...(options.plugins || [])
        ],
        resolve: {
            alias: {
                '@': path.resolve(__dirname, '../'),
                '@modules': path.resolve(__dirname, '../modules'),
                '@pages': path.resolve(__dirname, '../pages'),
            },
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
