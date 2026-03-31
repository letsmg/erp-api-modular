import { computed, ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { router } from '@inertiajs/vue3';
import { toggleProductFeatured, deleteProduct } from '@/modules/product/services/product-api';

export function useProductIndex(initialData = {}) {
    const search = ref(initialData.initialFilters?.search || '');
    const showOnlyBlocked = ref(String(initialData.initialFilters?.blocked || '0') === '1');
    const showOnlyActive = ref(String(initialData.initialFilters?.active || '0') === '1');
    const products = ref(initialData.products || []);
    const meta = ref(initialData.meta || { current_page: 1, last_page: 1, per_page: 12, total: 0 });
    const loading = ref(false);
    const deletingId = ref(null);
    const togglingFeaturedId = ref(null);

    const loadProducts = async (page = 1) => {
        loading.value = true;

        try {
            const response = await router.get(
                route('products.index'),
                {
                    page,
                    search: search.value || undefined,
                    blocked: showOnlyBlocked.value ? 1 : 0,
                    active: showOnlyActive.value ? 1 : 0,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        products.value = page.props.products || [];
                        meta.value = page.props.meta || meta.value;
                    },
                }
            );
        } finally {
            loading.value = false;
        }
    };

    const debouncedLoad = debounce(() => loadProducts(1), 300);

    watch(search, (value) => {
        if (value.length > 2 || value.length === 0) {
            debouncedLoad();
        }
    });

    watch(showOnlyBlocked, () => {
        // Se marcar bloqueados, desmarcar ativos (são mutuamente exclusivos)
        if (showOnlyBlocked.value) {
            showOnlyActive.value = false;
        }
        debouncedLoad();
    });

    watch(showOnlyActive, () => {
        // Se marcar ativos, desmarcar bloqueados (são mutuamente exclusivos)
        if (showOnlyActive.value) {
            showOnlyBlocked.value = false;
        }
        debouncedLoad();
    });

    const paginationPages = computed(() => {
        const totalPages = meta.value.last_page || 1;
        return Array.from({ length: totalPages }, (_, index) => index + 1);
    });

    const formatCurrency = (value) => {
        return Number(value || 0).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        });
    };

    const handleToggleFeatured = async (productId) => {
        togglingFeaturedId.value = productId;

        try {
            // Optimistic update - update immediately
            const currentProduct = products.value.find(p => p.id === productId);
            if (currentProduct) {
                currentProduct.is_featured = !currentProduct.is_featured;
            }

            await router.patch(
                route('products.toggle-featured', productId),
                {},
                {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // Check for updated product in flash data
                        const updatedProduct = page.props.flash?.product;
                        if (updatedProduct) {
                            products.value = products.value.map((product) =>
                                product.id === productId ? { ...product, ...updatedProduct } : product,
                            );
                        }
                    },
                    onError: () => {
                        // Revert optimistic update on error
                        if (currentProduct) {
                            currentProduct.is_featured = !currentProduct.is_featured;
                        }
                    }
                }
            );
        } finally {
            togglingFeaturedId.value = null;
        }
    };

    const handleDelete = async (productId) => {
        deletingId.value = productId;

        try {
            await router.delete(
                route('products.destroy', productId),
                {
                    onSuccess: () => {
                        if (products.value.length === 1 && meta.value.current_page > 1) {
                            loadProducts(meta.value.current_page - 1);
                            return;
                        }
                        loadProducts(meta.value.current_page);
                    }
                }
            );
        } finally {
            deletingId.value = null;
        }
    };

    return {
        search,
        showOnlyBlocked,
        showOnlyActive,
        products,
        meta,
        loading,
        deletingId,
        togglingFeaturedId,
        paginationPages,
        formatCurrency,
        loadProducts,
        handleToggleFeatured,
        handleDelete,
    };
}
