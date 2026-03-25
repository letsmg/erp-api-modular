import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';

export function useStoreIndex(props) {
    const page = usePage();

    // --- ESTADO DOS FILTROS ---
    const search = ref(props.filters?.search || '');
    const minPrice = ref(props.filters?.min_price || '');
    const maxPrice = ref(props.filters?.max_price || '');
    const brand = ref(props.filters?.brand || '');

    // --- LÓGICA DE FILTRAGEM (SERVER-SIDE) ---
    const filterProducts = debounce(() => {
        router.get(route('store.index'), {
            search: search.value.length >= 3 ? search.value : '',
            min_price: minPrice.value,
            max_price: maxPrice.value,
            brand: brand.value
        }, {
            preserveState: true,
            preserveScroll: false, // Volta ao topo para ver a paginação sticky
            replace: true
        });
    }, 500);

    watch([search, minPrice, maxPrice, brand], () => filterProducts());

    // --- MODAL DE TERMOS (LGPD) ---
    const showTermsModal = ref(false);
    const termsAccepted = ref(false);

    const acceptTerms = () => {
        if (!termsAccepted.value) return;

        router.post(route('store.terms.accept'), {}, {
            preserveScroll: true,
            onSuccess: () => {
                localStorage.setItem('erp_terms_accepted', 'true');
                showTermsModal.value = false;
            }
        });
    };

    // --- CONTROLE DO CARROSSEL ---
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

    // --- CICLO DE VIDA ---
    onMounted(() => {
        // Checagem LGPD
        if (!localStorage.getItem('erp_terms_accepted')) {
            showTermsModal.value = true;
        }

        // Atalho CTRL+M para termos
        const handleKeyDown = (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'm') {
                e.preventDefault();
                showTermsModal.value = true;
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        
        // Auto-play do carrossel
        timer = setInterval(() => scroll('hero-carousel', 'right'), 7000);

        onUnmounted(() => {
            window.removeEventListener('keydown', handleKeyDown);
            if (timer) clearInterval(timer);
        });
    });

    // --- SEO DATA ---
    const seoData = computed(() => page.props.store_seo ?? {
        title: "Vitrine Premium | ERP Zenite",
        description: "Explore nossa seleção exclusiva de produtos de alta qualidade.",
        h1: "Catálogo de Produtos"
    });

    return {
        search, minPrice, maxPrice, brand,
        showTermsModal, termsAccepted, acceptTerms,
        scroll, seoData
    };
}