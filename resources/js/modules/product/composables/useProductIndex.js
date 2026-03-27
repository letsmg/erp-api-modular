import { computed, ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { fetchProductList, toggleProductFeatured, deleteProduct } from '@/modules/product/services/product-api';

export function useProductIndex(initialFilters = {}) {
    const search = ref(initialFilters.search || '');
    const showOnlyBlocked = ref(String(initialFilters.blocked || '0') === '1');
    const products = ref([]);
    const meta = ref({ current_page: 1, last_page: 1, per_page: 12, total: 0 });
    const loading = ref(true);
    const deletingId = ref(null);
    const togglingFeaturedId = ref(null);

    const loadProducts = async (page = 1) => {
        loading.value = true;

        try {
            const response = await fetchProductList({
                page,
                search: search.value || undefined,
                blocked: showOnlyBlocked.value ? 1 : 0,
            });

            products.value = response.data.data ?? [];
            meta.value = response.data.meta ?? meta.value;
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
            const response = await toggleProductFeatured(productId);
            const updatedProduct = response.data.data;

            products.value = products.value.map((product) =>
                product.id === productId ? { ...product, ...updatedProduct } : product,
            );
        } finally {
            togglingFeaturedId.value = null;
        }
    };

    const handleDelete = async (productId) => {
        deletingId.value = productId;

        try {
            await deleteProduct(productId);

            if (products.value.length === 1 && meta.value.current_page > 1) {
                await loadProducts(meta.value.current_page - 1);
                return;
            }

            await loadProducts(meta.value.current_page);
        } finally {
            deletingId.value = null;
        }
    };

    return {
        search,
        showOnlyBlocked,
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
