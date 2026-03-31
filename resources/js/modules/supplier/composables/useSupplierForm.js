import { onMounted, onUnmounted, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { clearFormData, fillFormData, maskCEP, maskCNPJ, maskPhone } from '@/lib/utils';
import { getValidationErrors } from '@/lib/api/client';
import { createSupplier, fetchSupplier, updateSupplier } from '@/modules/supplier/services/supplier-api';

const states = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];

function createBaseForm() {
    return {
        company_name: '',
        email: '',
        cnpj: '',
        state_registration: '',
        address: '',
        neighborhood: '',
        city: '',
        state: '',
        zip_code: '',
        contact_name_1: '',
        phone_1: '',
        contact_name_2: '',
        phone_2: '',
        is_active: true,
        errors: {},
        processing: false,
    };
}

function applySupplier(form, supplier) {
    Object.assign(form, {
        company_name: supplier.company_name || '',
        email: supplier.email || '',
        cnpj: supplier.cnpj || '',
        state_registration: supplier.state_registration || '',
        address: supplier.address || '',
        neighborhood: supplier.neighborhood || '',
        city: supplier.city || '',
        state: supplier.state || '',
        zip_code: supplier.zip_code || '',
        contact_name_1: supplier.contact_name_1 || '',
        phone_1: supplier.phone_1 || '',
        contact_name_2: supplier.contact_name_2 || '',
        phone_2: supplier.phone_2 || '',
        is_active: Boolean(supplier.is_active ?? true),
        errors: {},
    });
}

export function useSupplierForm(options = {}) {
    const { supplierId = null, enableShortcuts = false } = options;
    const form = reactive(createBaseForm());
    form.data = () => {
        const { errors, processing, data, clearErrors, ...payload } = form;
        return payload;
    };
    form.clearErrors = () => {
        form.errors = {};
    };

    const loading = ref(Boolean(supplierId));
    const inputErrors = ref({ cnpj: false, zip_code: false, phone_1: false, phone_2: false });

    const validateAndMask = (field, value, maskFn) => {
        inputErrors.value[field] = /[a-zA-Z]/.test(value);
        form[field] = maskFn(value);
    };

    const handleCNPJ = (event) => validateAndMask('cnpj', event.target.value, maskCNPJ);
    const handleCEP = (event) => validateAndMask('zip_code', event.target.value, maskCEP);
    const handlePhone1 = (event) => validateAndMask('phone_1', event.target.value, maskPhone);
    const handlePhone2 = (event) => validateAndMask('phone_2', event.target.value, maskPhone);

    const filler = () => fillFormData(form);
    const clearer = () => {
        clearFormData(form);
        form.state = '';
        form.errors = {};
    };

    const submit = async () => {
        form.processing = true;
        form.errors = {};

        try {
            const payload = form.data();

            if (supplierId) {
                await updateSupplier(supplierId, payload);
            } else {
                await createSupplier(payload);
            }

            router.visit(route('suppliers.index'));
        } catch (error) {
            form.errors = getValidationErrors(error);
        } finally {
            form.processing = false;
        }
    };

    onMounted(async () => {
        if (supplierId) {
            const response = await fetchSupplier(supplierId);
            applySupplier(form, response.data.data);
            loading.value = false;
        }

        if (enableShortcuts) {
            window.addEventListener('magic-fill', filler);
            window.addEventListener('magic-clear', clearer);
        }
    });

    onUnmounted(() => {
        if (enableShortcuts) {
            window.removeEventListener('magic-fill', filler);
            window.removeEventListener('magic-clear', clearer);
        }
    });

    return {
        form,
        states,
        loading,
        inputErrors,
        handleCNPJ,
        handleCEP,
        handlePhone1,
        handlePhone2,
        filler,
        clearer,
        submit,
    };
}
