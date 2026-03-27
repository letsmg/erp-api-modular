import apiClient from '@/lib/api/client';

export function fetchUsers() {
    return apiClient.get(route('api.users.index'));
}

export function fetchUser(userId) {
    return apiClient.get(route('api.users.show', userId));
}

export function createUser(payload) {
    return apiClient.post(route('api.users.store'), payload);
}

export function updateUser(userId, payload) {
    return apiClient.put(route('api.users.update', userId), payload);
}

export function toggleUserStatus(userId) {
    return apiClient.patch(route('api.users.toggle', userId));
}

export function resetUserPassword(userId) {
    return apiClient.patch(route('api.users.reset', userId));
}

export function deleteUser(userId) {
    return apiClient.delete(route('api.users.destroy', userId));
}
