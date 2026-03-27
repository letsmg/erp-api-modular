import axios from 'axios';

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

export const apiClient = axios.create({
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
});

if (csrfToken) {
    apiClient.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && [401, 419].includes(error.response.status)) {
            window.location.href = '/login';
        }

        return Promise.reject(error);
    },
);

export function getValidationErrors(error: any): Record<string, string> {
    const errors = error?.response?.data?.errors;

    if (!errors || typeof errors !== 'object') {
        return {};
    }

    return Object.entries(errors).reduce<Record<string, string>>((carry, [field, messages]) => {
        carry[field] = Array.isArray(messages) ? String(messages[0] ?? '') : String(messages ?? '');
        return carry;
    }, {});
}

export default apiClient;
