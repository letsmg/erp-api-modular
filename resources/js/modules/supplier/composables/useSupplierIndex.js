import { computed, onMounted, ref } from 'vue';
import { deleteSupplier, fetchSuppliers, updateSupplier } from '@/modules/supplier/services/supplier-api';

export function useSupplierIndex() {
    const suppliers = ref([]);
    const meta = ref({ current_page: 1, last_page: 1, per_page: 15, total: 0 });
    const loading = ref(true);
    const processingId = ref(null);

    const loadSuppliers = async (page = 1) => {
        loading.value = true;

        try {
            const response = await fetchSuppliers({ page });
            suppliers.value = response.data.data ?? [];
            meta.value = response.data.meta ?? meta.value;
        } finally {
            loading.value = false;
        }
    };

    const paginationPages = computed(() => Array.from({ length: meta.value.last_page || 1 }, (_, index) => index + 1));

    const toggleStatus = async (supplier) => {
        processingId.value = supplier.id;

        try {
            const response = await updateSupplier(supplier.id, {
                ...supplier,
                is_active: !supplier.is_active,
            });

            const updated = response.data.data;
            suppliers.value = suppliers.value.map((item) => item.id === supplier.id ? updated : item);
        } finally {
            processingId.value = null;
        }
    };

    const destroy = async (supplierId) => {
        processingId.value = supplierId;

        try {
            await deleteSupplier(supplierId);
            await loadSuppliers(meta.value.current_page);
        } finally {
            processingId.value = null;
        }
    };

    onMounted(() => {
        loadSuppliers();
    });

    return {
        suppliers,
        meta,
        loading,
        processingId,
        paginationPages,
        loadSuppliers,
        toggleStatus,
        destroy,
    };
}
