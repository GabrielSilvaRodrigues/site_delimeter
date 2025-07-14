class LocalStorageManager {
    constructor(storageKey = 'diario_local') {
        this.storageKey = storageKey;
    }

    getAll() {
        try {
            return JSON.parse(localStorage.getItem(this.storageKey) || '[]');
        } catch (error) {
            console.error('Erro ao ler localStorage:', error);
            return [];
        }
    }

    save(data) {
        try {
            const records = this.getAll();
            records.push(data);
            localStorage.setItem(this.storageKey, JSON.stringify(records));
            return true;
        } catch (error) {
            console.error('Erro ao salvar no localStorage:', error);
            return false;
        }
    }

    update(records) {
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(records));
            return true;
        } catch (error) {
            console.error('Erro ao atualizar localStorage:', error);
            return false;
        }
    }

    remove(id) {
        try {
            const records = this.getAll();
            const updatedRecords = records.filter(r => r.id !== id);
            return this.update(updatedRecords);
        } catch (error) {
            console.error('Erro ao remover do localStorage:', error);
            return false;
        }
    }

    getUnsynchronized() {
        return this.getAll().filter(record => !record.sincronizado);
    }

    markAsSynchronized(id) {
        const records = this.getAll();
        const record = records.find(r => r.id === id);
        if (record) {
            record.sincronizado = true;
            return this.update(records);
        }
        return false;
    }

    removesynchronized() {
        const records = this.getAll();
        const unsynchronized = records.filter(r => !r.sincronizado);
        return this.update(unsynchronized);
    }

    clear() {
        try {
            localStorage.removeItem(this.storageKey);
            return true;
        } catch (error) {
            console.error('Erro ao limpar localStorage:', error);
            return false;
        }
    }
}
