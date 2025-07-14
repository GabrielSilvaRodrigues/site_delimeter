class ApiClient {
    constructor() {
        this.baseUrl = '';
        this.defaultHeaders = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
    }

    async request(url, options = {}) {
        const config = {
            method: 'GET',
            headers: { ...this.defaultHeaders, ...options.headers },
            ...options
        };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const text = await response.text();
            
            // Tentar parsear JSON
            try {
                return JSON.parse(text);
            } catch (parseError) {
                // Tentar extrair JSON do HTML contaminado
                const extracted = this.extractJSONFromHTML(text);
                if (extracted) return extracted;
                
                // Se não conseguir, retornar o texto
                return { success: true, data: text };
            }
        } catch (error) {
            console.error('API Request failed:', error);
            throw error;
        }
    }

    extractJSONFromHTML(text) {
        const patterns = [
            /\{"success":(true|false).*?\}(?=<|$)/,
            /\{[^}]*"success"[^}]*\}/
        ];

        for (const pattern of patterns) {
            const match = text.match(pattern);
            if (match) {
                try {
                    return JSON.parse(match[0]);
                } catch (e) {
                    continue;
                }
            }
        }

        return null;
    }

    async get(url, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const fullUrl = queryString ? `${url}?${queryString}` : url;
        return this.request(fullUrl);
    }

    async post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async put(url, data) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async delete(url, data = null) {
        const options = {
            method: 'DELETE'
        };
        
        if (data) {
            options.body = JSON.stringify(data);
        }
        
        return this.request(url, options);
    }

    async postForm(url, formData) {
        return this.request(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
    }
}
