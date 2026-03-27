import apiClient from '@/lib/api/client';

export function login(payload) {
    return apiClient.post(route('api.auth.login'), payload);
}

export function forgotPassword(payload) {
    return apiClient.post(route('api.password.email'), payload);
}

export function logout() {
    return apiClient.post(route('api.logout'));
}

export function me() {
    return apiClient.get(route('api.auth.me'));
}
