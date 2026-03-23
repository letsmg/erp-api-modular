import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';

export function useStoreIndex(props) {
    // --- FILTROS ---
    const search = ref(props.filters?.search || '');
    const minPrice = ref(props.filters?.min_price || '');
    const maxPrice = ref(props.filters?.max_price || '');
    const brand = ref(props.filters?.brand || '');
    const category = ref(props.filters?.category || '');

    const filterProducts = debounce(() => {
        const searchTerm = search.value.length > 0 && search.value.length < 3 ? '' : search.value;

        router.get(route('store.index'), {
            search: searchTerm,
            min_price: minPrice.value,
            max_price: maxPrice.value,
            brand: brand.value,
            category: category.value
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 500);

    watch([search, minPrice, maxPrice, brand, category], () => {
        filterProducts();
    });

    // --- MODAL ---
    const isModalOpen = ref(false);
    const selectedProduct = ref(null);

    const openDetails = (p) => { 
        selectedProduct.value = p; 
        isModalOpen.value = true; 
    };

    const closeModal = () => {
        isModalOpen.value = false;
        selectedProduct.value = null;
    };

    // --- CARROSSEL ---
    let timer = null;
    const scroll = (id, direction) => {
        const el = document.getElementById(id);
        if (!el) return;
        const isAtEnd = el.scrollLeft + el.offsetWidth >= el.scrollWidth - 10;
        if (direction === 'right' && isAtEnd) {
            el.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            const offset = direction === 'left' ? -el.offsetWidth : el.offsetWidth;
            el.scrollBy({ left: offset, behavior: 'smooth' });
        }
    };

    onMounted(() => { 
        timer = setInterval(() => scroll('hero-carousel', 'right'), 7000); 
    });

    onUnmounted(() => {
        if (timer) clearInterval(timer);
    });

    // --- SEO ---
    const page = usePage();
    const seoData = computed(() => {
        return page.props.store_seo ?? {
            title: "Vitrine Premium",
            description: "ERP Vue Laravel - Portfólio de E-commerce",
            keywords: "laravel, vue, portfolio",
            h1: "Explore Nossa Vitrine"
        };
    });

    return {
        // Filtros
        search, minPrice, maxPrice, brand, category,
        // Modal
        isModalOpen, selectedProduct, openDetails, closeModal,
        // Carrossel
        scroll,
        // SEO
        seoData
    };
}