import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

// Criar arquivos .vue virtuais que importam dos módulos
function createVirtualPagesPlugin() {
    return {
        name: 'virtual-pages',
        resolveId(id: string) {
            // Mapear páginas virtuais para módulos
            if (id.startsWith('virtual:')) {
                return id; // Usar ID direto sem prefixo especial
            }
            return null;
        },
        load(id: string) {
            // Remover prefixo virtual: para processamento
            const cleanId = id.replace('virtual:', '');
            
            // Store pages
            if (cleanId === 'Store/Index') {
                return `
                    <script setup>
                    import StoreHomePage from '../modules/store/pages/StoreHomePage.vue';
                    </script>
                    <template>
                        <StoreHomePage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'Store/Show') {
                return `
                    <script setup>
                    import StoreProductPage from '../modules/store/pages/StoreProductPage.vue';
                    </script>
                    <template>
                        <StoreProductPage v-bind="$attrs" />
                    </template>
                `;
            }

            // Auth pages
            if (cleanId === 'Auth/Login') {
                return `
                    <script setup>
                    import LoginPage from '../modules/auth/pages/LoginPage.vue';
                    </script>
                    <template>
                        <LoginPage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'Auth/ForgotPassword') {
                return `
                    <script setup>
                    import ForgotPasswordPage from '../modules/auth/pages/ForgotPasswordPage.vue';
                    </script>
                    <template>
                        <ForgotPasswordPage v-bind="$attrs" />
                    </template>
                `;
            }

            // Product pages
            if (cleanId === 'Product/Index') {
                return `
                    <script setup>
                    import ProductIndexPage from '../modules/product/pages/ProductIndexPage.vue';
                    </script>
                    <template>
                        <ProductIndexPage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'Product/Create') {
                return `
                    <script setup>
                    import ProductCreatePage from '../modules/product/pages/ProductCreatePage.vue';
                    </script>
                    <template>
                        <ProductCreatePage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'Product/Edit') {
                return `
                    <script setup>
                    import ProductEditPage from '../modules/product/pages/ProductEditPage.vue';
                    </script>
                    <template>
                        <ProductEditPage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'Product/Preview') {
                return `
                    <script setup>
                    import ProductPreviewPage from '../modules/product/pages/ProductPreviewPage.vue';
                    </script>
                    <template>
                        <ProductPreviewPage v-bind="$attrs" />
                    </template>
                `;
            }

            // User pages
            if (cleanId === 'User/Index') {
                return `
                    <script setup>
                    import UserIndexPage from '../modules/user/pages/UserIndexPage.vue';
                    </script>
                    <template>
                        <UserIndexPage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'User/Create') {
                return `
                    <script setup>
                    import UserCreatePage from '../modules/user/pages/UserCreatePage.vue';
                    </script>
                    <template>
                        <UserCreatePage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'User/Edit') {
                return `
                    <script setup>
                    import UserEditPage from '../modules/user/pages/UserEditPage.vue';
                    </script>
                    <template>
                        <UserEditPage v-bind="$attrs" />
                    </template>
                `;
            }

            // Supplier pages
            if (cleanId === 'Supplier/Index') {
                return `
                    <script setup>
                    import SupplierIndexPage from '../modules/supplier/pages/SupplierIndexPage.vue';
                    </script>
                    <template>
                        <SupplierIndexPage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'Supplier/Create') {
                return `
                    <script setup>
                    import SupplierCreatePage from '../modules/supplier/pages/SupplierCreatePage.vue';
                    </script>
                    <template>
                        <SupplierCreatePage v-bind="$attrs" />
                    </template>
                `;
            }
            if (cleanId === 'Supplier/Edit') {
                return `
                    <script setup>
                    import SupplierEditPage from '../modules/supplier/pages/SupplierEditPage.vue';
                    </script>
                    <template>
                        <SupplierEditPage v-bind="$attrs" />
                    </template>
                `;
            }

            // Reports pages
            if (cleanId === 'Reports/Index') {
                return `
                    <script setup>
                    import ReportIndexPage from '../modules/report/pages/ReportIndexPage.vue';
                    </script>
                    <template>
                        <ReportIndexPage v-bind="$attrs" />
                    </template>
                `;
            }

            // Dashboard page
            if (cleanId === 'Dashboard') {
                return `
                    <script setup>
                    import DashboardPage from '../modules/app/pages/DashboardPage.vue';
                    </script>
                    <template>
                        <DashboardPage v-bind="$attrs" />
                    </template>
                `;
            }

            return null;
        }
    };
}

export default defineConfig({
    server: {
        port: 5173,
        host: '127.0.0.1', // Força IPv4
        strictPort: true
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
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
