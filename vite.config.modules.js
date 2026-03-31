// Plugin Vite para resolver páginas Inertia para módulos Vue.js
import { defineConfig } from 'vite';
import path from 'path';

// Mapeamento de páginas para módulos
const pageModuleMapping = {
    'Store/Index': path.resolve(__dirname, './resources/js/modules/store/pages/StoreHomePage.vue'),
    'Store/Show': path.resolve(__dirname, './resources/js/modules/store/pages/StoreProductPage.vue'),
    'Auth/Login': path.resolve(__dirname, './resources/js/modules/auth/pages/LoginPage.vue'),
    'Auth/ForgotPassword': path.resolve(__dirname, './resources/js/modules/auth/pages/ForgotPasswordPage.vue'),
    'Products/Index': path.resolve(__dirname, './resources/js/modules/product/pages/ProductIndexPage.vue'),
    'Products/Create': path.resolve(__dirname, './resources/js/modules/product/pages/ProductCreatePage.vue'),
    'Products/Edit': path.resolve(__dirname, './resources/js/modules/product/pages/ProductEditPage.vue'),
    'Products/Preview': path.resolve(__dirname, './resources/js/modules/product/pages/ProductPreviewPage.vue'),
    'Users/Index': path.resolve(__dirname, './resources/js/modules/user/pages/UserIndexPage.vue'),
    'Users/Create': path.resolve(__dirname, './resources/js/modules/user/pages/UserCreatePage.vue'),
    'Users/Edit': path.resolve(__dirname, './resources/js/modules/user/pages/UserEditPage.vue'),
    'Suppliers/Index': path.resolve(__dirname, './resources/js/modules/supplier/pages/SupplierIndexPage.vue'),
    'Suppliers/Create': path.resolve(__dirname, './resources/js/modules/supplier/pages/SupplierCreatePage.vue'),
    'Suppliers/Edit': path.resolve(__dirname, './resources/js/modules/supplier/pages/SupplierEditPage.vue'),
    'Reports/Index': path.resolve(__dirname, './resources/js/modules/report/pages/ReportIndexPage.vue'),
    'Dashboard': path.resolve(__dirname, './resources/js/modules/app/pages/DashboardPage.vue'),
};

// Plugin para criar arquivos de página virtuais
function createVirtualPagesPlugin() {
    return {
        name: 'virtual-pages',
        resolveId(id) {
            // Verificar se é uma página Inertia
            if (pageModuleMapping[id]) {
                return id; // Retornar o ID original
            }
            return null;
        },
        load(id) {
            // Se for uma página mapeada, criar um componente proxy
            if (pageModuleMapping[id]) {
                const modulePath = pageModuleMapping[id];
                return `
                    <script setup>
                    import ModuleComponent from '${modulePath.replace(/\\/g, '\\\\')}';
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

export default function createModuleViteConfig() {
    return defineConfig({
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
}
