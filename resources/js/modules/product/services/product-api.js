import apiClient from '@/lib/api/client';
import { toFormData } from '@/lib/api/form-data';

export function fetchProductList(params = {}) {
    return apiClient.get(route('api.products.index'), { params });
}

export function fetchProductFormOptions() {
    return apiClient.get(route('api.products.form-options'));
}

export function fetchProduct(productId) {
    return apiClient.get(route('api.products.show', productId));
}

export function createProduct(payload) {
    return apiClient.post(route('api.products.store'), toFormData(payload), {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}

export function updateProduct(productId, payload) {
    const formData = toFormData({
        ...payload,
        _method: 'PUT',
    });

    return apiClient.post(route('api.products.update', productId), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}

export function toggleProductFeatured(productId) {
    return apiClient.patch(route('api.products.toggle-featured', productId));
}

export function deleteProduct(productId) {
    return apiClient.delete(route('api.products.destroy', productId));
}
