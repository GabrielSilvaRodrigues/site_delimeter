class AlimentoManager {
    constructor(apiClient, messageManager) {
        this.apiClient = apiClient;
        this.messageManager = messageManager;
        this.alimentosDisponiveis = [];
        this.alimentosSelecionados = [];
        this.fallbackAlimentos = [
            { id_alimento: 1, descricao_alimento: 'Arroz branco cozido', dados_nutricionais: 'Carboidratos: 28g, Proteínas: 2.7g, Gorduras: 0.3g por 100g' },
            { id_alimento: 2, descricao_alimento: 'Feijão preto cozido', dados_nutricionais: 'Carboidratos: 24g, Proteínas: 8.9g, Gorduras: 0.5g por 100g' },
            { id_alimento: 3, descricao_alimento: 'Frango grelhado (peito)', dados_nutricionais: 'Carboidratos: 0g, Proteínas: 31g, Gorduras: 3.6g por 100g' },
            { id_alimento: 4, descricao_alimento: 'Brócolis cozido', dados_nutricionais: 'Carboidratos: 5g, Proteínas: 3g, Gorduras: 0.3g por 100g' },
            { id_alimento: 5, descricao_alimento: 'Batata doce cozida', dados_nutricionais: 'Carboidratos: 20g, Proteínas: 1.6g, Gorduras: 0.1g por 100g' }
        ];
        this.apiAlimentos = '/api/alimentos';
    }

    async carregarAlimentos() {
        try {
            const alimentos = await this.apiClient.get(`${this.apiAlimentos}/listar`);
            
            if (Array.isArray(alimentos) && alimentos.length > 0) {
                this.alimentosDisponiveis = alimentos;
                console.log('Alimentos carregados da API:', alimentos.length);
            } else {
                this.alimentosDisponiveis = this.fallbackAlimentos;
                console.log('Usando alimentos fallback');
            }
            
            return this.alimentosDisponiveis;
        } catch (error) {
            console.error('Erro ao carregar alimentos:', error);
            this.alimentosDisponiveis = this.fallbackAlimentos;
            console.log('Usando alimentos fallback devido ao erro');
            return this.fallbackAlimentos;
        }
    }

    buscarAlimentos(termo) {
        if (this.alimentosDisponiveis.length === 0) {
            this.carregarAlimentos().then(() => this.buscarAlimentos(termo));
            return [];
        }

        const termoBusca = termo.toLowerCase();
        return this.alimentosDisponiveis.filter(alimento => 
            alimento.descricao_alimento.toLowerCase().includes(termoBusca)
        );
    }

    selecionarAlimento(alimento) {
        const jaExiste = this.alimentosSelecionados.find(a => a.id_alimento === alimento.id_alimento);
        
        if (jaExiste) {
            this.alimentosSelecionados = this.alimentosSelecionados.filter(a => a.id_alimento !== alimento.id_alimento);
            console.log('Alimento removido da seleção');
        } else {
            this.alimentosSelecionados.push({
                id_alimento: alimento.id_alimento,
                descricao_alimento: alimento.descricao_alimento,
                dados_nutricionais: alimento.dados_nutricionais || ''
            });
            console.log('Alimento adicionado à seleção');
        }
        
        return this.alimentosSelecionados;
    }

    removerAlimentoSelecionado(index) {
        if (index >= 0 && index < this.alimentosSelecionados.length) {
            const alimento = this.alimentosSelecionados.splice(index, 1)[0];
            console.log('Alimento removido:', alimento.descricao_alimento);
            return true;
        }
        return false;
    }

    adicionarAlimentoPersonalizado(nome, dadosNutricionais = '') {
        const alimentoPersonalizado = {
            id_alimento: 'custom_' + Date.now(),
            descricao_alimento: nome.trim(),
            dados_nutricionais: dadosNutricionais || 'Alimento personalizado'
        };

        this.alimentosDisponiveis.push(alimentoPersonalizado);
        this.alimentosSelecionados.push(alimentoPersonalizado);
        
        return alimentoPersonalizado;
    }

    limparSelecao() {
        this.alimentosSelecionados = [];
    }

    getAlimentosSelecionados() {
        return [...this.alimentosSelecionados];
    }

    getAlimentosDisponiveis() {
        return [...this.alimentosDisponiveis];
    }

    isAlimentoSelecionado(id_alimento) {
        return this.alimentosSelecionados.some(a => a.id_alimento === id_alimento);
    }

    async carregarAlimentosDoRegistro(id_diario) {
        try {
            const alimentos = await this.apiClient.get(`${this.apiAlimentos}/buscar-por-diario`, { id_diario });
            
            if (Array.isArray(alimentos) && alimentos.length > 0) {
                return alimentos;
            }
            
            return [];
        } catch (error) {
            console.error('Erro ao carregar alimentos do registro:', error);
            return [];
        }
    }
}
