// Custom Inertia page resolver for modular Vue.js structure
const path = require('path');

class ModulePageResolver {
    constructor() {
        this.modulePageMapping = {
            'Store/Index': path.resolve(__dirname, '../resources/js/modules/store/pages/StoreHomePage.vue'),
            'Store/Show': path.resolve(__dirname, '../resources/js/modules/store/pages/StoreProductPage.vue'),
            'Auth/Login': path.resolve(__dirname, '../resources/js/modules/auth/pages/LoginPage.vue'),
            'Auth/ForgotPassword': path.resolve(__dirname, '../resources/js/modules/auth/pages/ForgotPasswordPage.vue'),
            'Products/Index': path.resolve(__dirname, '../resources/js/modules/product/pages/ProductIndexPage.vue'),
            'Products/Create': path.resolve(__dirname, '../resources/js/modules/product/pages/ProductCreatePage.vue'),
            'Products/Edit': path.resolve(__dirname, '../resources/js/modules/product/pages/ProductEditPage.vue'),
            'Products/Preview': path.resolve(__dirname, '../resources/js/modules/product/pages/ProductPreviewPage.vue'),
            'Users/Index': path.resolve(__dirname, '../resources/js/modules/user/pages/UserIndexPage.vue'),
            'Users/Create': path.resolve(__dirname, '../resources/js/modules/user/pages/UserCreatePage.vue'),
            'Users/Edit': path.resolve(__dirname, '../resources/js/modules/user/pages/UserEditPage.vue'),
            'Suppliers/Index': path.resolve(__dirname, '../resources/js/modules/supplier/pages/SupplierIndexPage.vue'),
            'Suppliers/Create': path.resolve(__dirname, '../resources/js/modules/supplier/pages/SupplierCreatePage.vue'),
            'Suppliers/Edit': path.resolve(__dirname, '../resources/js/modules/supplier/pages/SupplierEditPage.vue'),
            'Reports/Index': path.resolve(__dirname, '../resources/js/modules/report/pages/ReportIndexPage.vue'),
            'Dashboard': path.resolve(__dirname, '../resources/js/modules/app/pages/DashboardPage.vue'),
        };
    }

    resolve(pageName) {
        return this.modulePageMapping[pageName] || null;
    }
}

module.exports = ModulePageResolver;
