// API Client para usar com Sanctum
class SanctumApiClient {
    constructor(baseURL = '/api/v1') {
        this.baseURL = baseURL;
        this.token = localStorage.getItem('sanctum_token');
    }

    // Definir token
    setToken(token) {
        this.token = token;
        localStorage.setItem('sanctum_token', token);
    }

    // Remover token
    removeToken() {
        this.token = null;
        localStorage.removeItem('sanctum_token');
    }

    // Headers padrão
    getHeaders() {
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    // Request genérico
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: this.getHeaders(),
            ...options,
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Erro na requisição');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Métodos HTTP
    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return this.request(url, { method: 'GET' });
    }

    async post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data),
        });
    }

    async put(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    }

    async patch(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'PATCH',
            body: JSON.stringify(data),
        });
    }

    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }

    // Upload de arquivos
    async upload(endpoint, formData) {
        const headers = this.getHeaders();
        delete headers['Content-Type']; // Deixa o navegador definir o boundary

        return this.request(endpoint, {
            method: 'POST',
            body: formData,
            headers,
        });
    }

    // Métodos de autenticação
    async login(email, password, deviceName = 'web_app') {
        try {
            const response = await this.post('/auth/login', {
                email,
                password,
                device_name: deviceName,
            });

            if (response.data?.token) {
                this.setToken(response.data.token);
            }

            return response;
        } catch (error) {
            this.removeToken();
            throw error;
        }
    }

    async logout() {
        try {
            await this.post('/auth/logout');
            this.removeToken();
        } catch (error) {
            this.removeToken();
            throw error;
        }
    }

    async logoutAll() {
        try {
            await this.post('/auth/logout-all');
            this.removeToken();
        } catch (error) {
            this.removeToken();
            throw error;
        }
    }

    async me() {
        return this.get('/auth/me');
    }

    async getTokens() {
        return this.get('/auth/tokens');
    }

    async revokeToken(tokenId) {
        return this.delete(`/auth/tokens/${tokenId}`);
    }

    // Verificar se está autenticado
    isAuthenticated() {
        return !!this.token;
    }
}

// Instância global
const api = new SanctumApiClient();

export default api;
