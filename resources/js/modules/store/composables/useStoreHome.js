import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { debounce } from 'lodash';
import { router, usePage } from '@inertiajs/vue3';
import { fetchStoreHome } from '@/modules/store/services/store-api';

export function useStoreHome(initialFilters = {}) {
    const page = usePage();
    const search = ref(initialFilters.search || '');
    const minPrice = ref(initialFilters.min_price || '');
    const maxPrice = ref(initialFilters.max_price || '');
    const brand = ref(initialFilters.brand || '');
    const products = ref([]);
    const featuredProducts = ref([]);
    const onSaleProducts = ref([]);
    const brands = ref([]);
    const meta = ref({ current_page: 1, last_page: 1, per_page: 9, total: 0 });
    const loading = ref(true);
    const showTermsModal = ref(false);
    const termsAccepted = ref(false);

    const getNormalizedLength = (text) => text?.normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim().length || 0;

    const loadHome = async (pageNumber = 1) => {
        loading.value = true;

        try {
            const response = await fetchStoreHome({
                page: pageNumber,
                search: search.value || undefined,
                min_price: minPrice.value || undefined,
                max_price: maxPrice.value || undefined,
                brand: brand.value || undefined,
            });

            const payload = response.data.data ?? {};
            products.value = payload.products ?? [];
            featuredProducts.value = payload.featured_products ?? [];
            onSaleProducts.value = payload.on_sale_products ?? [];
            brands.value = payload.brands ?? [];
            meta.value = response.data.meta ?? meta.value;
        } finally {
            loading.value = false;
        }
    };

    const refreshWithHistory = debounce(async () => {
        await loadHome(1);

        router.replace({
            url: route('store.index', {
                search: search.value || undefined,
                min_price: minPrice.value || undefined,
                max_price: maxPrice.value || undefined,
                brand: brand.value || undefined,
            }),
            component: page.component,
            props: page.props,
        });
    }, 400);

    watch(search, (value) => {
        const length = getNormalizedLength(value);
        if (length >= 3 || length === 0) {
            refreshWithHistory();
        }
    });

    watch([minPrice, maxPrice, brand], () => {
        refreshWithHistory();
    });

    const acceptTerms = () => {
        if (!termsAccepted.value) return;

        localStorage.setItem('erp_terms_accepted', 'true');
        showTermsModal.value = false;
        router.post(route('store.terms.accept'), {}, { preserveScroll: true });
    };

    const scroll = (id, direction) => {
        const element = document.getElementById(id);
        if (!element) return;

        const isAtEnd = element.scrollLeft + element.offsetWidth >= element.scrollWidth - 10;

        if (direction === 'right' && isAtEnd) {
            element.scrollTo({ left: 0, behavior: 'smooth' });
            return;
        }

        const offset = direction === 'left' ? -element.offsetWidth : element.offsetWidth;
        element.scrollBy({ left: offset, behavior: 'smooth' });
    };

    const handleKeyDown = (event) => {
        if (event.key === 'Escape') {
            showTermsModal.value = false;
        }
    };

    let autoPlay = null;

    onMounted(async () => {
        await loadHome();
        showTermsModal.value = localStorage.getItem('erp_terms_accepted') !== 'true';
        window.addEventListener('keydown', handleKeyDown);

        autoPlay = setInterval(() => {
            if (document.getElementById('hero-carousel')) {
                scroll('hero-carousel', 'right');
            }
        }, 7000);
    });

    onUnmounted(() => {
        if (autoPlay) clearInterval(autoPlay);
        window.removeEventListener('keydown', handleKeyDown);
    });

    const seoData = computed(() => page.props.store_seo ?? {
        title: 'Vitrine Premium | ERP Zenite',
        description: 'Explore nossa selecao exclusiva de produtos.',
        h1: 'Catalogo de Produtos',
    });

    const paginationPages = computed(() => Array.from({ length: meta.value.last_page || 1 }, (_, index) => index + 1));

    return {
        search,
        minPrice,
        maxPrice,
        brand,
        products,
        featuredProducts,
        onSaleProducts,
        brands,
        meta,
        loading,
        showTermsModal,
        termsAccepted,
        acceptTerms,
        scroll,
        seoData,
        paginationPages,
        loadHome,
    };
}
