import apiClient from '@/lib/api/client';

export function login(payload) {
    return apiClient.post(route('login.post'), {
        email: payload.email,
        password: payload.password,
        remember: payload.remember,
    });
}

export function forgotPassword(payload) {
    return apiClient.post(route('password.email.web'), payload);
}

export function logout() {
    return apiClient.post(route('logout'));
}
