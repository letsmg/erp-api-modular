import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { fetchStoreProduct } from '@/modules/store/services/store-api';

export function useStoreProduct(productSlug) {
    const page = usePage();
    const product = ref(null);
    const relatedProducts = ref([]);
    const loading = ref(true);
    const activeImageIndex = ref(0);

    const loadProduct = async () => {
        loading.value = true;

        try {
            const response = await fetchStoreProduct(productSlug);
            product.value = response.data.data?.product ?? null;
            relatedProducts.value = response.data.data?.related_products ?? [];
        } finally {
            loading.value = false;
        }
    };

    watch(() => product.value?.id, () => {
        activeImageIndex.value = 0;
    });

    const seoData = computed(() => product.value?.seo || {});
    const isPromoActive = computed(() => {
        if (!product.value?.promo_price) return false;
        const now = new Date();
        const start = product.value.promo_start_at ? new Date(product.value.promo_start_at) : null;
        const end = product.value.promo_end_at ? new Date(product.value.promo_end_at) : null;
        if (start && now < start) return false;
        if (end && now > end) return false;
        return true;
    });
    const isAdmin = computed(() => page.props.auth?.user?.access_level === 1);

    const nextImage = () => {
        if (product.value?.images?.length > 0) {
            activeImageIndex.value = (activeImageIndex.value + 1) % product.value.images.length;
        }
    };

    const prevImage = () => {
        if (product.value?.images?.length > 0) {
            activeImageIndex.value = (activeImageIndex.value - 1 + product.value.images.length) % product.value.images.length;
        }
    };

    const getImageUrl = (path) => {
        if (!path) return null;
        const cleanPath = path.startsWith('products/') ? path : `products/${path}`;
        return `/storage/${cleanPath}`;
    };

    const formatCurrency = (value) => Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

    return {
        product,
        relatedProducts,
        loading,
        activeImageIndex,
        seoData,
        isPromoActive,
        isAdmin,
        loadProduct,
        nextImage,
        prevImage,
        getImageUrl,
        formatCurrency,
    };
}
