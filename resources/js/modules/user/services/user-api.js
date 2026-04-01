import apiClient from '@/lib/api/client';

export function fetchUsers() {
    return apiClient.get(route('users.index'));
}

export function fetchUser(userId) {
    return apiClient.get(route('users.show', userId));
}

export function createUser(payload) {
    return apiClient.post(route('users.store'), payload);
}

export function updateUser(userId, payload) {
    return apiClient.put(route('users.update', userId), payload);
}

export function toggleUserStatus(userId) {
    return apiClient.patch(route('users.toggle', userId));
}

export function resetUserPassword(userId) {
    return apiClient.patch(route('users.reset', userId));
}

export function deleteUser(userId) {
    return apiClient.delete(route('users.destroy', userId));
}
