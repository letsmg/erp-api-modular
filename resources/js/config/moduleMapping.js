// Alias configuration for Vite resolver
export const pathAliases = {
    '@': './resources/js',
    '@modules': './resources/js/modules',
    '@pages': './resources/js/pages',
};

// Module mapping for Inertia pages
export const modulePageMapping = {
    // Store Module
    'Store/Index': '@modules/store/pages/StoreHomePage.vue',
    'Store/Show': '@modules/store/pages/StoreProductPage.vue',
    
    // Auth Module
    'Auth/Login': '@modules/auth/pages/LoginPage.vue',
    'Auth/ForgotPassword': '@modules/auth/pages/ForgotPasswordPage.vue',
    
    // Product Module
    'Products/Index': '@modules/product/pages/ProductIndexPage.vue',
    'Products/Create': '@modules/product/pages/ProductCreatePage.vue',
    'Products/Edit': '@modules/product/pages/ProductEditPage.vue',
    'Products/Preview': '@modules/product/pages/ProductPreviewPage.vue',
    
    // User Module
    'Users/Index': '@modules/user/pages/UserIndexPage.vue',
    'Users/Create': '@modules/user/pages/UserCreatePage.vue',
    'Users/Edit': '@modules/user/pages/UserEditPage.vue',
    
    // Supplier Module
    'Suppliers/Index': '@modules/supplier/pages/SupplierIndexPage.vue',
    'Suppliers/Create': '@modules/supplier/pages/SupplierCreatePage.vue',
    'Suppliers/Edit': '@modules/supplier/pages/SupplierEditPage.vue',
    
    // Report Module
    'Reports/Index': '@modules/report/pages/ReportIndexPage.vue',
    
    // App Module
    'Dashboard': '@modules/app/pages/DashboardPage.vue',
};
