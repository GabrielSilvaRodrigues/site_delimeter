class DiarioAlimentosManager {
    constructor(idPaciente) {
        this.idPaciente = idPaciente;
        this.apiClient = new ApiClient();
        this.messageManager = new MessageManager();
        this.storageManager = new LocalStorageManager();
        this.alimentosSelecionados = [];
        this.alimentosDisponiveis = [];
        
        this.API_DIARIO = '/api/diario';
        this.API_ALIMENTOS = '/api/alimentos';
        
        this.initializeEventListeners();
        this.loadData();
    }

    async loadData() {
        await this.carregarAlimentos();
        await this.carregarHistorico();
        this.mostrarBotaoSincronizar();
        this.configurarFiltros();
    }

    initializeEventListeners() {
        // Busca de alimentos
        const buscarInput = document.getElementById('buscar_alimento');
        if (buscarInput) {
            buscarInput.addEventListener('input', (e) => {
                const termo = e.target.value.trim();
                if (termo.length >= 2) {
                    this.buscarAlimentos(termo);
                } else {
                    this.ocultarResultadosBusca();
                }
            });
        }

        // Formulário de diário
        const diarioForm = document.getElementById('diarioForm');
        if (diarioForm) {
            diarioForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Botão limpar
        const limparBtn = document.getElementById('limparBtn');
        if (limparBtn) {
            limparBtn.addEventListener('click', () => this.limparFormulario());
        }

        // Filtros
        const filtrarBtn = document.getElementById('filtrarBtn');
        if (filtrarBtn) {
            filtrarBtn.addEventListener('click', () => this.aplicarFiltros());
        }
    }

    async carregarAlimentos() {
        try {
            const response = await this.apiClient.get(`${this.API_ALIMENTOS}/listar`);
            
            if (response && Array.isArray(response)) {
                this.alimentosDisponiveis = response;
            } else if (response.data && Array.isArray(response.data)) {
                this.alimentosDisponiveis = response.data;
            } else {
                // Fallback
                this.alimentosDisponiveis = this.getFallbackAlimentos();
            }
            
            console.log('Alimentos carregados:', this.alimentosDisponiveis.length);
        } catch (error) {
            console.error('Erro ao carregar alimentos:', error);
            this.alimentosDisponiveis = this.getFallbackAlimentos();
        }
    }

    getFallbackAlimentos() {
        return [
            { id_alimento: 1, descricao_alimento: 'Arroz branco cozido', dados_nutricionais: 'Calorias: 130kcal/100g' },
            { id_alimento: 2, descricao_alimento: 'Feijão preto cozido', dados_nutricionais: 'Calorias: 132kcal/100g' },
            { id_alimento: 3, descricao_alimento: 'Frango grelhado (peito)', dados_nutricionais: 'Calorias: 165kcal/100g' },
            // ...outros alimentos
        ];
    }

    async carregarHistorico() {
        try {
            this.mostrarCarregando();
            
            const response = await this.apiClient.get(`${this.API_DIARIO}/buscar-por-paciente`, {
                id_paciente: this.idPaciente
            });
            
            let dados = [];
            if (Array.isArray(response)) {
                dados = response;
            } else if (response.data && Array.isArray(response.data)) {
                dados = response.data;
            }
            
            this.renderizarHistorico(dados);
            
        } catch (error) {
            console.error('Erro ao carregar histórico:', error);
            this.mostrarErroHistorico(error);
        }
    }

    renderizarHistorico(registros) {
        const container = document.getElementById('historicoContainer');
        
        if (registros.length === 0) {
            container.innerHTML = this.getEmptyHistoryHTML();
            return;
        }

        let html = '<div style="display: grid; gap: 15px;">';
        
        registros.forEach(registro => {
            html += this.getRegistroHTML(registro);
        });
        
        html += '</div>';
        container.innerHTML = html;
        
        // Carregar alimentos para cada registro
        registros.forEach(registro => {
            const idDiario = registro.id_diario || registro.id;
            if (idDiario) {
                this.carregarAlimentosDoRegistro(idDiario);
            }
        });
    }

    getRegistroHTML(registro) {
        const dataFormatada = registro.data_diario ? 
            new Date(registro.data_diario + 'T00:00:00').toLocaleDateString('pt-BR') : '-';
        const idDiario = registro.id_diario || registro.id;
        
        return `
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: #f9f9f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="margin: 0; color: #ff9800;">📅 ${dataFormatada}</h3>
                    ${idDiario ? `
                        <button onclick="diarioManager.excluirRegistro(${idDiario})" 
                                style="background: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            🗑️ Excluir
                        </button>
                    ` : ''}
                </div>
                <p style="margin: 10px 0; color: #333; line-height: 1.5;">
                    ${registro.descricao_diario || 'Sem descrição'}
                </p>
                <div id="alimentos-${idDiario}" style="margin-top: 10px;">
                    <p style="color: #666; font-style: italic;">Carregando alimentos...</p>
                </div>
            </div>
        `;
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        data.id_paciente = this.idPaciente;
        data.alimentos_selecionados = this.alimentosSelecionados;
        
        if (this.alimentosSelecionados.length === 0 && !data.descricao_diario.trim()) {
            this.messageManager.warning('Por favor, adicione alimentos ou escreva uma descrição do que você comeu.');
            return;
        }
        
        try {
            const response = await this.apiClient.post(`${this.API_DIARIO}/criar`, data);
            
            if (response && response.success) {
                this.messageManager.success('Registro do diário salvo com sucesso!');
                this.limparFormulario();
                await this.carregarHistorico();
            } else {
                throw new Error(response?.error || 'Erro desconhecido da API');
            }
        } catch (error) {
            console.error('Erro ao salvar:', error);
            
            const salvarLocal = confirm(`Erro de conexão: ${error.message}\n\nDeseja salvar o registro localmente?`);
            
            if (salvarLocal) {
                this.salvarLocalmente(data);
                this.messageManager.info('Registro salvo localmente! Use o botão de sincronização quando a conexão for restabelecida.');
            }
        }
    }

    salvarLocalmente(dados) {
        const registroLocal = {
            id: 'local_' + Date.now(),
            data_diario: dados.data_diario,
            descricao_diario: dados.descricao_diario,
            id_paciente: dados.id_paciente,
            alimentos: this.alimentosSelecionados.map(a => ({
                id_alimento: a.id_alimento,
                descricao_alimento: a.descricao_alimento,
                dados_nutricionais: a.dados_nutricionais
            })),
            timestamp: new Date().toISOString(),
            sincronizado: false
        };
        
        let registrosLocais = this.storageManager.get('diario_local', []);
        registrosLocais.push(registroLocal);
        this.storageManager.set('diario_local', registrosLocais);
        
        this.limparFormulario();
        this.mostrarBotaoSincronizar();
        this.adicionarRegistroLocalAoHistorico(registroLocal);
    }

    buscarAlimentos(termo) {
        const termoBusca = termo.toLowerCase();
        const resultados = this.alimentosDisponiveis.filter(alimento => 
            alimento.descricao_alimento.toLowerCase().includes(termoBusca)
        );
        
        this.mostrarResultadosBusca(resultados, termo);
    }

    mostrarResultadosBusca(resultados, termo) {
        const container = document.getElementById('resultados-container');
        const section = document.getElementById('busca-resultados');
        
        if (resultados.length === 0) {
            container.innerHTML = this.getNoResultsHTML(termo);
        } else {
            container.innerHTML = this.getResultsHTML(resultados);
        }
        
        section.style.display = 'block';
    }

    selecionarAlimento(id, descricao, dadosNutricionais = '') {
        const jaExiste = this.alimentosSelecionados.find(a => a.id_alimento === id);
        
        if (jaExiste) {
            this.alimentosSelecionados = this.alimentosSelecionados.filter(a => a.id_alimento !== id);
        } else {
            this.alimentosSelecionados.push({
                id_alimento: id,
                descricao_alimento: descricao,
                dados_nutricionais: dadosNutricionais
            });
        }
        
        this.atualizarListaAlimentosSelecionados();
        
        // Atualizar resultados da busca
        const termo = document.getElementById('buscar_alimento').value.trim();
        if (termo.length >= 2) {
            this.buscarAlimentos(termo);
        }
    }

    // ...existing code...
    // Adicionar métodos restantes seguindo o mesmo padrão OOP
}

// Expor métodos globais para compatibilidade com HTML inline
window.selecionarAlimento = function(id, descricao, dados) {
    if (window.diarioManager) {
        window.diarioManager.selecionarAlimento(id, descricao, dados);
    }
};