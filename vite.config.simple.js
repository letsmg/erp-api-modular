// Plugin Vite para criar páginas virtuais que apontam para módulos
import { defineConfig } from 'vite';
import path from 'path';

// Criar arquivos .vue virtuais que importam dos módulos
function createVirtualPagesPlugin() {
    return {
        name: 'virtual-pages',
        resolveId(id) {
            // Mapear páginas virtuais para módulos
            if (id.startsWith('Store/')) {
                return id;
            }
            return null;
        },
        load(id) {
            // Criar componente virtual que importa do módulo
            if (id === 'Store/Index') {
                return `
                    <script setup>
                    import StoreHomePage from '../modules/store/pages/StoreHomePage.vue';
                    </script>
                    <template>
                        <StoreHomePage v-bind="$attrs" />
                    </template>
                `;
            }
            if (id === 'Store/Show') {
                return `
                    <script setup>
                    import StoreProductPage from '../modules/store/pages/StoreProductPage.vue';
                    </script>
                    <template>
                        <StoreProductPage v-bind="$attrs" />
                    </template>
                `;
            }
            return null;
        }
    };
}

export default defineConfig({
    plugins: [
        createVirtualPagesPlugin(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@modules': path.resolve(__dirname, './resources/js/modules'),
            '@pages': path.resolve(__dirname, './resources/js/pages'),
        },
        extensions: ['.js', '.ts', '.vue', '.json'],
    },
});
