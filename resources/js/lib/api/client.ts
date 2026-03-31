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
    (response) => {
        // Se for uma resposta de login bem-sucedida com redirect
        if (response.data?.redirect) {
            return response;
        }
        
        return response;
    },
    (error) => {
        if (error.response && [401, 419].includes(error.response.status)) {
            window.location.href = '/login';
        }

        // Log 422 errors for debugging
        if (error.response?.status === 422) {
            console.log('Validation errors:', error.response.data);
        }

        return Promise.reject(error);
    },
);

export function getValidationErrors(error: any): Record<string, string> {
    const errors = error?.response?.data?.errors;

    if (!errors || typeof errors !== 'object') {
        // Se não houver erros de validação, verificar mensagem geral
        const message = error?.response?.data?.message;
        if (message) {
            console.log('General error message:', message);
            return { _general: message };
        }
        return {};
    }

    const formattedErrors: Record<string, string> = {};
    
    Object.entries(errors).forEach(([field, messages]) => {
        const errorMessage = Array.isArray(messages) 
            ? String(messages[0] ?? '') 
            : String(messages ?? '');
        
        formattedErrors[field] = errorMessage;
        
        // Log individual field errors
        console.log(`Field ${field}:`, errorMessage);
    });

    return formattedErrors;
}

export default apiClient;
