class LocalStorageManager {
    constructor(prefix = 'delimeter_') {
        this.prefix = prefix;
    }

    set(key, value) {
        try {
            const serialized = JSON.stringify(value);
            localStorage.setItem(this.prefix + key, serialized);
            return true;
        } catch (error) {
            console.error('Erro ao salvar no localStorage:', error);
            return false;
        }
    }

    get(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(this.prefix + key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (error) {
            console.error('Erro ao ler do localStorage:', error);
            return defaultValue;
        }
    }

    remove(key) {
        try {
            localStorage.removeItem(this.prefix + key);
            return true;
        } catch (error) {
            console.error('Erro ao remover do localStorage:', error);
            return false;
        }
    }

    clear() {
        try {
            const keys = Object.keys(localStorage);
            keys.forEach(key => {
                if (key.startsWith(this.prefix)) {
                    localStorage.removeItem(key);
                }
            });
            return true;
        } catch (error) {
            console.error('Erro ao limpar localStorage:', error);
            return false;
        }
    }

    has(key) {
        return localStorage.getItem(this.prefix + key) !== null;
    }

    getAll() {
        try {
            const result = {};
            const keys = Object.keys(localStorage);
            
            keys.forEach(key => {
                if (key.startsWith(this.prefix)) {
                    const cleanKey = key.replace(this.prefix, '');
                    result[cleanKey] = this.get(cleanKey);
                }
            });
            
            return result;
        } catch (error) {
            console.error('Erro ao obter todos os itens:', error);
            return {};
        }
    }
}
