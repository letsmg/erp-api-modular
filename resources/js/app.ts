import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import '../css/app.css';
import '@/lib/api/client';

const appName = import.meta.env.VITE_APP_NAME || 'ERP Api Modular';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        // Import direto dos módulos usando alias @/ (apenas arquivos existentes)
        const moduleMap: Record<string, () => Promise<any>> = {
            'Dashboard': () => import('@/modules/app/pages/DashboardPage.vue'),
            'Auth/Login': () => import('@/modules/auth/pages/LoginPage.vue'),
            'Auth/ForgotPassword': () => import('@/modules/auth/pages/ForgotPasswordPage.vue'),
            'Store/Index': () => import('@/modules/store/pages/StoreHomePage.vue'),
            'Store/Show': () => import('@/modules/store/pages/StoreProductPage.vue'),
            'Products/Index': () => import('@/modules/product/pages/ProductIndexPage.vue'),
            'Products/Create': () => import('@/modules/product/pages/ProductCreatePage.vue'),
            'Products/Edit': () => import('@/modules/product/pages/ProductEditPage.vue'),
            'Products/Preview': () => import('@/modules/product/pages/ProductPreviewPage.vue'),
            'Users/Index': () => import('@/modules/user/pages/UserIndexPage.vue'),
            'Users/Create': () => import('@/modules/user/pages/UserCreatePage.vue'),
            'Users/Edit': () => import('@/modules/user/pages/UserEditPage.vue'),
            'Suppliers/Index': () => import('@/modules/supplier/pages/SupplierIndexPage.vue'),
            'Suppliers/Create': () => import('@/modules/supplier/pages/SupplierCreatePage.vue'),
            'Suppliers/Edit': () => import('@/modules/supplier/pages/SupplierEditPage.vue'),
            'Reports/Index': () => import('@/modules/report/pages/ReportIndexPage.vue'),
        };

        const pageImporter = moduleMap[name];
        if (pageImporter) {
            return pageImporter();
        }

        // Fallback para Error.vue
        return import('./pages/Error.vue');
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, (window as any).Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
