import apiClient from '@/lib/api/client';

export function fetchSuppliers(params = {}) {
    return apiClient.get(route('api.suppliers.index'), { params });
}

export function fetchSupplier(supplierId) {
    return apiClient.get(route('api.suppliers.show', supplierId));
}

export function createSupplier(payload) {
    return apiClient.post(route('api.suppliers.store'), payload);
}

export function updateSupplier(supplierId, payload) {
    return apiClient.put(route('api.suppliers.update', supplierId), payload);
}

export function deleteSupplier(supplierId) {
    return apiClient.delete(route('api.suppliers.destroy', supplierId));
}
