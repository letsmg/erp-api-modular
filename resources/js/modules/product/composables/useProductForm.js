import { computed, onMounted, onUnmounted, reactive, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { getValidationErrors } from '@/lib/api/client';
import { clearFormData, fillFormData } from '@/lib/utils';
import { createProduct, fetchProduct, fetchProductFormOptions, updateProduct } from '@/modules/product/services/product-api';

const page = usePage();

function createBaseForm() {
    return {
        supplier_id: '',
        description: '',
        brand: '',
        category_id: '',
        model: '',
        size: '',
        collection: '',
        gender: 'Unissex',
        barcode: '',
        stock_quantity: 0,
        is_active: true,
        is_featured: false,
        images: [],
        existing_images: [],
        new_images: [],
        removed_images: [],
        cost_price: 0,
        sale_price: 0,
        promo_price: null,
        promo_start_at: '',
        promo_end_at: '',
        weight: '',
        width: '',
        height: '',
        length: '',
        free_shipping: false,
        meta_title: '',
        meta_description: '',
        meta_keywords: [],
        slug: '',
        h1: '',
        h2: '',
        text1: '',
        text2: '',
        schema_markup: '',
        google_tag_manager: '',
        errors: {},
        processing: false,
    };
}

function formatDate(dateString) {
    if (!dateString) return '';
    return dateString.replace(' ', 'T').substring(0, 16);
}

function hydrateForm(form, product) {
    form.description = product.description || '';
    form.supplier_id = product.supplier_id || '';
    form.category_id = product.category_id || '';
    form.barcode = product.barcode || '';
    form.brand = product.brand || '';
    form.model = product.model || '';
    form.collection = product.collection || '';
    form.size = product.size || '';
    form.gender = product.gender || 'Unissex';
    form.stock_quantity = product.stock_quantity || 0;
    form.slug = product.slug || '';
    form.cost_price = Number(product.cost_price) || 0;
    form.sale_price = Number(product.sale_price) || 0;
    form.promo_price = product.promo_price ? Number(product.promo_price) : null;
    form.promo_start_at = formatDate(product.promo_start_at);
    form.promo_end_at = formatDate(product.promo_end_at);
    form.weight = Number(product.weight) || 0;
    form.width = Number(product.width) || 0;
    form.height = Number(product.height) || 0;
    form.length = Number(product.length) || 0;
    form.free_shipping = Boolean(product.free_shipping);
    form.is_active = Boolean(product.is_active);
    form.is_featured = Boolean(product.is_featured);
    form.google_tag_manager = product.seo?.google_tag_manager || '';
    form.meta_title = product.seo?.meta_title || '';
    form.meta_description = product.seo?.meta_description || '';
    form.meta_keywords = product.seo?.meta_keywords
        ? product.seo.meta_keywords.split(',').map((item) => item.trim()).filter(Boolean)
        : [];
    form.h1 = product.seo?.h1 || '';
    form.h2 = product.seo?.h2 || '';
    form.text1 = product.seo?.text1 || '';
    form.text2 = product.seo?.text2 || '';
    form.schema_markup = product.seo?.schema_markup || '';
    form.existing_images = [...(product.images || [])];
    form.new_images = [];
    form.removed_images = [];
    form.errors = {};
}

function buildPayload(form, isEdit) {
    return {
        supplier_id: form.supplier_id || null,
        description: form.description,
        brand: form.brand,
        category_id: form.category_id || null,
        model: form.model,
        size: form.size,
        collection: form.collection,
        gender: form.gender,
        barcode: form.barcode,
        stock_quantity: form.stock_quantity,
        is_active: form.is_active,
        is_featured: form.is_featured,
        images: isEdit ? undefined : form.images,
        existing_images: isEdit ? form.existing_images.map((image) => image.id) : undefined,
        new_images: isEdit ? form.new_images : undefined,
        removed_images: isEdit ? form.removed_images : undefined,
        cost_price: form.cost_price,
        sale_price: form.sale_price,
        promo_price: form.promo_price,
        promo_start_at: form.promo_start_at,
        promo_end_at: form.promo_end_at,
        weight: form.weight,
        width: form.width,
        height: form.height,
        length: form.length,
        free_shipping: form.free_shipping,
        meta_title: form.meta_title,
        meta_description: form.meta_description,
        meta_keywords: Array.isArray(form.meta_keywords) ? form.meta_keywords.join(', ') : form.meta_keywords,
        slug: form.slug,
        h1: form.h1,
        h2: form.h2,
        text1: form.text1,
        text2: form.text2,
        schema_markup: form.schema_markup,
        google_tag_manager: form.google_tag_manager,
    };
}

export function useProductForm({ productId, initialProduct, initialSuppliers, initialCategories, enableShortcuts = false } = {}) {
    const form = reactive(createBaseForm());
    
    form.data = () => {
        const { errors, processing, existing_images, new_images, removed_images, ...payload } = form;
        return payload;
    };
    
    const activeTab = ref('geral');
    const imagePreviews = ref([]);
    const newImagePreviews = ref([]);
    const tagInput = ref('');
    const suppliers = ref(initialSuppliers || []);
    const categories = ref(initialCategories || []);
    const loading = ref(false);

    // Initialize form with product data if provided
    if (initialProduct && Object.keys(initialProduct).length > 0) {
        hydrateForm(form, initialProduct);
    }

    const loadFormDependencies = async () => {
        if (initialSuppliers && initialCategories) {
            // Data already provided by controller
            return;
        }

        loading.value = true;

        try {
            const [optionsResponse, productResponse] = await Promise.all([
                fetchProductFormOptions(),
                productId ? fetchProduct(productId) : Promise.resolve(null),
            ]);

            suppliers.value = optionsResponse.data.data?.suppliers ?? [];
            categories.value = optionsResponse.data.data?.categories ?? [];

            if (productResponse) {
                hydrateForm(form, productResponse.data.data);
            }
        } finally {
            loading.value = false;
        }
    };

    const addTag = () => {
        const value = tagInput.value.trim();

        if (value && !form.meta_keywords.includes(value)) {
            form.meta_keywords.push(value);
            tagInput.value = '';
        }
    };

    const removeTag = (index) => {
        form.meta_keywords.splice(index, 1);
    };

    const handleImageUpload = (event) => {
        const files = Array.from(event.target.files || []);
        const currentTotal = productId
            ? form.existing_images.length + form.new_images.length
            : form.images.length;

        if (currentTotal + files.length > 6) {
            window.alert('Maximo de 6 fotos permitido.');
            return;
        }

        files.forEach((file) => {
            if (productId) {
                form.new_images.push(file);
                newImagePreviews.value.push(URL.createObjectURL(file));
                return;
            }

            form.images.push(file);
            imagePreviews.value.push(URL.createObjectURL(file));
        });
    };

    const removeImage = (index) => {
        const preview = imagePreviews.value[index];
        if (preview?.startsWith('blob:')) {
            URL.revokeObjectURL(preview);
        }

        form.images.splice(index, 1);
        imagePreviews.value.splice(index, 1);
    };

    const removeExistingImage = (index) => {
        const image = form.existing_images[index];
        form.removed_images.push(image.id);
        form.existing_images.splice(index, 1);
    };

    const removeNewImage = (index) => {
        const preview = newImagePreviews.value[index];
        if (preview?.startsWith('blob:')) {
            URL.revokeObjectURL(preview);
        }

        form.new_images.splice(index, 1);
        newImagePreviews.value.splice(index, 1);
    };

    const onDragEnd = () => {
        imagePreviews.value = form.images.map((file) => URL.createObjectURL(file));
    };

    const fillTestForm = () => fillFormData(form, suppliers.value, categories.value);

    const clearCurrentForm = () => {
        clearFormData(form);
        form.meta_keywords = [];
        form.errors = {};
        imagePreviews.value = [];
        newImagePreviews.value = [];
        tagInput.value = '';
    };

    const handleShortcut = (event) => {
        if (!enableShortcuts) {
            return;
        }

        // Verifica se é teclado numérico (location 3) - Ctrl+Alt pode não funcionar bem
        const isNumpad = event.location === 3 || event.code.startsWith('Numpad');
        
        // Para teclado numérico, usa apenas keyCode/key sem exigir Ctrl+Alt
        if (isNumpad) {
            if (event.key === '1' || event.code === 'Numpad1' || event.keyCode === 97 || event.keyCode === 49) {
                event.preventDefault();
                fillTestForm();
                return;
            }
            if (event.key === '2' || event.code === 'Numpad2' || event.keyCode === 98 || event.keyCode === 50) {
                event.preventDefault();
                clearCurrentForm();
                return;
            }
        }

        // Para teclado QWERTY, aceita tanto com Ctrl+Alt quanto sem Ctrl+Alt (caracteres especiais)
        // Aceita caracteres especiais ¹ e ² quando pressionados sozinhos
        if ((event.ctrlKey && event.altKey && (
            event.key === '1' || 
            event.code === 'Digit1' ||
            event.keyCode === 49 ||
            event.which === 49
        )) || (
            !event.ctrlKey && !event.altKey && (
                event.key === '¹' ||  // Caractere especial ¹
                event.key === '²'    // Caractere especial ²
            )
        )) {
            if (event.key === '¹' || event.key === '1') {
                event.preventDefault();
                fillTestForm();
            }
        }

        // Ctrl+Alt+2 ou caractere especial ² para limpar
        if ((event.ctrlKey && event.altKey && (
            event.key === '2' || 
            event.code === 'Digit2' ||
            event.keyCode === 50 ||
            event.which === 50
        )) || (
            !event.ctrlKey && !event.altKey && event.key === '²'
        )) {
            event.preventDefault();
            clearCurrentForm();
        }
    };

    const profitData = computed(() => {
        const cost = parseFloat(form.cost_price) || 0;
        const sale = parseFloat(form.sale_price) || 0;
        const profit = sale - cost;
        const margin = cost > 0 ? (profit / cost) * 100 : 0;

        return {
            value: profit.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }),
            percentage: margin.toFixed(2),
        };
    });

    const submit = async () => {
        form.processing = true;
        form.errors = {};

        try {
            const payload = buildPayload(form, Boolean(productId));

            if (productId) {
                await router.put(
                    route('products.update', productId),
                    payload,
                    {
                        onSuccess: () => {
                            router.visit(route('products.index'));
                        }
                    }
                );
            } else {
                await router.post(
                    route('products.store'),
                    payload,
                    {
                        onSuccess: () => {
                            router.visit(route('products.index'));
                        }
                    }
                );
            }
        } catch (error) {
            const validationErrors = getValidationErrors(error);
            form.errors = validationErrors;
            
            // Adicionar erros ao page.props.errors global para o layout exibir
            page.props.errors = { ...page.props.errors, ...validationErrors };
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
            form.processing = false;
        }
    };

    onMounted(async () => {
        await loadFormDependencies();
        window.addEventListener('keydown', handleShortcut);
    });

    onUnmounted(() => {
        window.removeEventListener('keydown', handleShortcut);
        [...imagePreviews.value, ...newImagePreviews.value].forEach((preview) => {
            if (preview?.startsWith('blob:')) {
                URL.revokeObjectURL(preview);
            }
        });
    });

    return {
        form,
        activeTab,
        imagePreviews,
        newImagePreviews,
        tagInput,
        suppliers,
        categories,
        loading,
        addTag,
        removeTag,
        handleImageUpload,
        removeImage,
        removeExistingImage,
        removeNewImage,
        onDragEnd,
        profitData,
        fillTestForm,
        clearCurrentForm,
        submit,
    };
}

