import apiClient from '@/lib/api/client';

export function fetchStoreHome(params = {}) {
    return apiClient.get(route('api.catalog.home'), { params });
}

export function fetchStoreProduct(slug) {
    return apiClient.get(route('api.catalog.products.show', slug));
}
