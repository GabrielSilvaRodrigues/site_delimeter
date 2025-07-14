class DiarioAlimentosManager {
    constructor(idPaciente) {
        this.idPaciente = idPaciente;
        this.apiClient = new ApiClient();
        this.messageManager = new MessageManager();
        this.storageManager = new LocalStorageManager('diario_local');
        this.alimentosSelecionados = [];
        this.alimentosDisponiveis = [];
        
        this.API_DIARIO = '/api/diario-alimentos';
        this.API_ALIMENTOS = '/api/alimentos';
        
        this.initializeElements();
        this.initializeEventListeners();
        this.loadData();
    }

    initializeElements() {
        this.elements = {
            form: document.getElementById('diarioForm'),
            buscarAlimento: document.getElementById('buscar_alimento'),
            alimentosList: document.getElementById('alimentosList'),
            buscaResultados: document.getElementById('buscaResultados'),
            resultadosContainer: document.getElementById('resultadosContainer'),
            historicoContainer: document.getElementById('historicoContainer'),
            limparBtn: document.getElementById('limparBtn'),
            filtrarBtn: document.getElementById('filtrarBtn'),
            filtroDataInicio: document.getElementById('filtro_data_inicio'),
            filtroDataFim: document.getElementById('filtro_data_fim')
        };
    }

    initializeEventListeners() {
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        if (this.elements.buscarAlimento) {
            this.elements.buscarAlimento.addEventListener('input', (e) => {
                const termo = e.target.value.trim();
                if (termo.length >= 2) {
                    this.buscarAlimentos(termo);
                } else {
                    this.ocultarResultadosBusca();
                }
            });
        }

        if (this.elements.limparBtn) {
            this.elements.limparBtn.addEventListener('click', () => this.limparFormulario());
        }

        if (this.elements.filtrarBtn) {
            this.elements.filtrarBtn.addEventListener('click', () => this.aplicarFiltros());
        }
    }

    async loadData() {
        await this.carregarAlimentos();
        await this.carregarHistorico();
        this.verificarRegistrosLocais();
        this.configurarFiltros();
        this.atualizarListaAlimentosSelecionados();
    }

    async carregarAlimentos() {
        try {
            const response = await this.apiClient.get(`${this.API_ALIMENTOS}/listar`);
            
            if (response && Array.isArray(response)) {
                this.alimentosDisponiveis = response;
            } else if (response.data && Array.isArray(response.data)) {
                this.alimentosDisponiveis = response.data;
            } else {
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
            { id_alimento: 4, descricao_alimento: 'Brócolis cozido', dados_nutricionais: 'Calorias: 25kcal/100g' },
            { id_alimento: 5, descricao_alimento: 'Batata doce cozida', dados_nutricionais: 'Calorias: 86kcal/100g' }
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

    mostrarCarregando() {
        if (this.elements.historicoContainer) {
            this.elements.historicoContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">🔄 Carregando histórico...</p>';
        }
    }

    mostrarErroHistorico(error) {
        if (this.elements.historicoContainer) {
            this.elements.historicoContainer.innerHTML = `
                <div style="text-align: center; padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px;">
                    <h3 style="color: #721c24;">❌ Erro ao carregar histórico</h3>
                    <p style="color: #721c24; margin: 10px 0; font-size: 14px;">${error.message}</p>
                    <button onclick="diarioManager.carregarHistorico()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                        🔄 Tentar Novamente
                    </button>
                </div>
            `;
        }
    }

    renderizarHistorico(registros) {
        if (!this.elements.historicoContainer) return;
        
        if (registros.length === 0) {
            this.elements.historicoContainer.innerHTML = this.getEmptyHistoryHTML();
            return;
        }

        let html = '<div style="display: grid; gap: 15px;">';
        
        registros.forEach(registro => {
            html += this.getRegistroHTML(registro);
        });
        
        html += '</div>';
        this.elements.historicoContainer.innerHTML = html;
        
        // Carregar alimentos para cada registro
        registros.forEach(registro => {
            const idDiario = registro.id_diario || registro.id;
            if (idDiario) {
                this.carregarAlimentosDoRegistro(idDiario);
            }
        });
    }

    getEmptyHistoryHTML() {
        return `
            <div style="text-align: center; padding: 20px; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px;">
                <h3 style="color: #1976d2;">📔 Nenhum registro encontrado</h3>
                <p style="color: #1976d2; margin: 10px 0;">Crie seu primeiro registro de alimentação usando o formulário acima!</p>
                <button onclick="diarioManager.carregarHistorico()" style="background: #2196f3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                    🔄 Atualizar
                </button>
            </div>
        `;
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
            alimentos: dados.alimentos_selecionados.map(a => ({
                id_alimento: a.id_alimento,
                descricao_alimento: a.descricao_alimento,
                dados_nutricionais: a.dados_nutricionais
            })),
            timestamp: new Date().toISOString(),
            sincronizado: false
        };
        
        if (this.storageManager.save(registroLocal)) {
            console.log('Registro salvo localmente:', registroLocal);
            this.limparFormulario();
            this.verificarRegistrosLocais();
            this.adicionarRegistroLocalAoHistorico(registroLocal);
        } else {
            this.messageManager.error('Erro ao salvar mesmo localmente. Tente novamente.');
        }
    }

    async buscarAlimentos(termo) {
        try {
            const response = await this.apiClient.get(`${this.API_ALIMENTOS}/buscar-por-descricao`, {
                descricao: termo
            });
            
            if (response.success && response.data) {
                this.mostrarResultadosBusca(response.data, termo);
            } else {
                this.mostrarResultadosBusca([], termo);
            }
        } catch (error) {
            console.error('Erro ao buscar alimentos:', error);
            this.messageManager.error('Erro ao buscar alimentos');
        }
    }

    mostrarResultadosBusca(resultados, termo) {
        if (!this.elements.resultadosContainer || !this.elements.buscaResultados) return;
        
        if (resultados.length === 0) {
            this.elements.resultadosContainer.innerHTML = this.getNoResultsHTML(termo);
        } else {
            this.elements.resultadosContainer.innerHTML = this.getResultsHTML(resultados);
        }
        
        this.elements.buscaResultados.style.display = 'block';
    }

    getNoResultsHTML(termo) {
        return `
            <div style="text-align: center; padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
                <p style="color: #856404; margin: 0;">Nenhum alimento encontrado para "${termo}"</p>
                <button onclick="diarioManager.adicionarAlimentoPersonalizado('${termo}')" 
                        style="background: #ff9800; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;">
                    ➕ Adicionar "${termo}" como alimento personalizado
                </button>
            </div>
        `;
    }

    getResultsHTML(resultados) {
        return resultados.map(alimento => {
            const jaSelecionado = this.alimentosSelecionados.some(a => a.id_alimento === alimento.id_alimento);
            
            return `
                <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: ${jaSelecionado ? '#e8f5e9' : '#fff'}; cursor: pointer;" 
                     onclick="diarioManager.selecionarAlimento(${alimento.id_alimento}, '${alimento.descricao_alimento.replace(/'/g, "\\'")}', '${(alimento.dados_nutricionais || '').replace(/'/g, "\\'")}')">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; color: #ff9800;">${alimento.descricao_alimento}</h4>
                        <span style="background: ${jaSelecionado ? '#4caf50' : '#ff9800'}; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px;">
                            ${jaSelecionado ? '✓ Selecionado' : '+ Adicionar'}
                        </span>
                    </div>
                    ${alimento.dados_nutricionais ? `<p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">${alimento.dados_nutricionais}</p>` : ''}
                </div>
            `;
        }).join('');
    }

    selecionarAlimento(id, descricao, dadosNutricionais = '') {
        const jaExiste = this.alimentosSelecionados.find(a => a.id_alimento === id);
        
        if (jaExiste) {
            this.alimentosSelecionados = this.alimentosSelecionados.filter(a => a.id_alimento !== id);
            console.log('Alimento removido da seleção');
        } else {
            this.alimentosSelecionados.push({
                id_alimento: id,
                descricao_alimento: descricao,
                dados_nutricionais: dadosNutricionais
            });
            console.log('Alimento adicionado à seleção');
        }
        
        this.atualizarListaAlimentosSelecionados();
        
        // Atualizar resultados da busca
        const termo = this.elements.buscarAlimento.value.trim();
        if (termo.length >= 2) {
            this.buscarAlimentos(termo);
        }
    }

    adicionarAlimentoPersonalizado(nome) {
        const descricao = prompt(`Digite a descrição completa do alimento "${nome}":`, nome);
        
        if (descricao && descricao.trim()) {
            const dadosNutricionais = prompt('Digite informações nutricionais (opcional):', 'Informações não disponíveis');
            
            const alimentoPersonalizado = {
                id_alimento: 'custom_' + Date.now(),
                descricao_alimento: descricao.trim(),
                dados_nutricionais: dadosNutricionais || 'Alimento personalizado'
            };

            this.alimentosDisponiveis.push(alimentoPersonalizado);
            this.alimentosSelecionados.push(alimentoPersonalizado);
            
            this.atualizarListaAlimentosSelecionados();
            
            // Limpar busca
            this.elements.buscarAlimento.value = '';
            this.ocultarResultadosBusca();
            
            this.messageManager.success(`Alimento "${descricao}" adicionado e selecionado!`);
        }
    }

    atualizarListaAlimentosSelecionados() {
        if (!this.elements.alimentosList) return;
        
        if (this.alimentosSelecionados.length === 0) {
            this.elements.alimentosList.innerHTML = '<p style="color: #666; margin: 0;">Nenhum alimento selecionado ainda.</p>';
            return;
        }
        
        let html = '<div style="display: grid; gap: 8px;">';
        
        this.alimentosSelecionados.forEach((alimento, index) => {
            html += `
                <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <div>
                        <strong style="color: #ff9800;">${alimento.descricao_alimento}</strong>
                        ${alimento.dados_nutricionais ? `<br><small style="color: #666;">${alimento.dados_nutricionais}</small>` : ''}
                    </div>
                    <button onclick="diarioManager.removerAlimentoSelecionado(${index})" 
                            style="background: #f44336; color: white; border: none; padding: 5px 8px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        ✕
                    </button>
                </div>
            `;
        });
        
        html += '</div>';
        html += `
            <div style="margin-top: 10px; padding: 10px; background: #e3f2fd; border-radius: 5px; border: 1px solid #bbdefb;">
                <p style="margin: 0; color: #1976d2; font-size: 14px;">
                    <strong>Total:</strong> ${this.alimentosSelecionados.length} alimento${this.alimentosSelecionados.length !== 1 ? 's' : ''} selecionado${this.alimentosSelecionados.length !== 1 ? 's' : ''}
                </p>
            </div>
        `;
        
        this.elements.alimentosList.innerHTML = html;
    }

    removerAlimentoSelecionado(index) {
        if (index >= 0 && index < this.alimentosSelecionados.length) {
            const alimento = this.alimentosSelecionados.splice(index, 1)[0];
            console.log('Alimento removido:', alimento.descricao_alimento);
            this.atualizarListaAlimentosSelecionados();
            
            // Atualizar resultados da busca se estiver visível
            if (this.elements.buscaResultados.style.display !== 'none') {
                const termo = this.elements.buscarAlimento.value.trim();
                if (termo.length >= 2) {
                    this.buscarAlimentos(termo);
                }
            }
        }
    }

    async carregarAlimentosDoRegistro(idDiario) {
        try {
            const response = await this.apiClient.get(`${this.API_ALIMENTOS}/buscar-por-diario`, {
                id_diario: idDiario
            });

            const container = document.getElementById(`alimentos-${idDiario}`);
            if (response.success && response.data && response.data.length > 0) {
                const html = response.data.map(alimento => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 5px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 3px; background: white;">
                        <span style="font-size: 14px;">${alimento.descricao_alimento}</span>
                        <button onclick="diarioManager.removerAlimentoDoRegistro(${idDiario}, ${alimento.id_alimento})" 
                                style="background: #f44336; color: white; border: none; padding: 2px 6px; border-radius: 2px; cursor: pointer; font-size: 11px;">
                            Remover
                        </button>
                    </div>
                `).join('');
                container.innerHTML = '<strong style="color: #ff9800;">Alimentos:</strong><div style="margin-top: 5px;">' + html + '</div>';
            } else {
                container.innerHTML = '<p style="color: #666; margin: 0; font-size: 14px;">Nenhum alimento registrado.</p>';
            }
        } catch (error) {
            console.error('Erro ao carregar alimentos do registro:', error);
            const container = document.getElementById(`alimentos-${idDiario}`);
            if (container) {
                container.innerHTML = '<p style="color: #f44336; margin: 0; font-size: 14px;">Erro ao carregar alimentos.</p>';
            }
        }
    }

    async excluirRegistro(idDiario) {
        if (!confirm('Tem certeza que deseja excluir este registro?')) {
            return;
        }

        try {
            const response = await this.apiClient.delete(`${this.API_DIARIO}/deletar`, {
                id: idDiario
            });

            if (response.success) {
                this.messageManager.success('Registro excluído com sucesso!');
                this.carregarHistorico();
            } else {
                this.messageManager.error(response.error || 'Erro ao excluir registro');
            }
        } catch (error) {
            console.error('Erro ao excluir registro:', error);
            this.messageManager.error('Erro ao excluir registro');
        }
    }

    async removerAlimentoDoRegistro(idDiario, idAlimento) {
        try {
            const response = await this.apiClient.post(`${this.API_DIARIO}/remover-alimento`, {
                id_diario: idDiario,
                id_alimento: idAlimento
            });

            if (response.success) {
                this.messageManager.success('Alimento removido com sucesso!');
                this.carregarAlimentosDoRegistro(idDiario);
            } else {
                this.messageManager.error(response.error || 'Erro ao remover alimento');
            }
        } catch (error) {
            console.error('Erro ao remover alimento:', error);
            this.messageManager.error('Erro ao remover alimento');
        }
    }

    limparFormulario() {
        if (this.elements.form) {
            this.elements.form.reset();
            document.getElementById('data_diario').value = new Date().toISOString().split('T')[0];
        }
        
        if (this.elements.buscarAlimento) {
            this.elements.buscarAlimento.value = '';
        }
        
        this.alimentosSelecionados = [];
        this.atualizarListaAlimentosSelecionados();
        this.ocultarResultadosBusca();
    }

    ocultarResultadosBusca() {
        if (this.elements.buscaResultados) {
            this.elements.buscaResultados.style.display = 'none';
        }
    }

    aplicarFiltros() {
        const dataInicio = this.elements.filtroDataInicio?.value;
        const dataFim = this.elements.filtroDataFim?.value;

        if (!dataInicio || !dataFim) {
            this.messageManager.warning('Selecione as datas de início e fim');
            return;
        }

        this.carregarHistoricoFiltrado(dataInicio, dataFim);
    }

    async carregarHistoricoFiltrado(dataInicio, dataFim) {
        try {
            const response = await this.apiClient.get(`${this.API_DIARIO}/buscar-por-periodo`, {
                id_paciente: this.idPaciente,
                data_inicio: dataInicio,
                data_fim: dataFim
            });

            if (response.success && response.data) {
                this.renderizarHistorico(response.data);
            } else {
                this.elements.historicoContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhum registro encontrado no período.</p>';
            }
        } catch (error) {
            console.error('Erro ao filtrar histórico:', error);
            this.messageManager.error('Erro ao filtrar histórico');
        }
    }

    configurarFiltros() {
        const hoje = new Date();
        const umaSemanaAtras = new Date(hoje.getTime() - 7 * 24 * 60 * 60 * 1000);
        
        if (this.elements.filtroDataInicio) {
            this.elements.filtroDataInicio.value = umaSemanaAtras.toISOString().split('T')[0];
        }
        if (this.elements.filtroDataFim) {
            this.elements.filtroDataFim.value = hoje.toISOString().split('T')[0];
        }
    }

    verificarRegistrosLocais() {
        const registrosNaoSincronizados = this.storageManager.getUnsynchronized();
        
        if (registrosNaoSincronizados.length > 0) {
            this.mostrarBotaoSincronizar(registrosNaoSincronizados.length);
        } else {
            this.removerBotaoSincronizar();
        }
    }

    mostrarBotaoSincronizar(quantidade) {
        this.removerBotaoSincronizar();
        
        this.syncButton = document.createElement('div');
        this.syncButton.id = 'sync-button';
        this.syncButton.style.cssText = `
            position: fixed; 
            top: 20px; 
            right: 20px; 
            background: #ff9800; 
            color: white; 
            padding: 12px 16px; 
            border-radius: 8px; 
            cursor: pointer; 
            z-index: 1000; 
            box-shadow: 0 4px 12px rgba(255,152,0,0.4);
            font-size: 14px;
            font-weight: bold;
            border: 2px solid #f57c00;
            transition: all 0.3s ease;
        `;
        
        this.syncButton.innerHTML = `
            📡 ${quantidade} registro(s) local(is)
            <br><small style="font-size: 11px; opacity: 0.9;">Clique para sincronizar</small>
        `;
        
        this.syncButton.onclick = () => this.sincronizarRegistrosLocais();
        
        document.body.appendChild(this.syncButton);
    }

    removerBotaoSincronizar() {
        if (this.syncButton) {
            this.syncButton.remove();
            this.syncButton = null;
        }
    }

    async sincronizarRegistrosLocais() {
        const registrosLocais = this.storageManager.getUnsynchronized();
        
        if (registrosLocais.length === 0) {
            this.messageManager.info('Não há registros locais para sincronizar.');
            return;
        }
        
        let sincronizados = 0;
        let erros = 0;
        
        if (this.syncButton) {
            this.syncButton.innerHTML = '⏳ Sincronizando...';
            this.syncButton.style.background = '#6c757d';
        }
        
        for (const registro of registrosLocais) {
            try {
                const result = await this.apiClient.post(`${this.API_DIARIO}/criar`, {
                    id_paciente: registro.id_paciente,
                    data_diario: registro.data_diario,
                    descricao_diario: registro.descricao_diario,
                    alimentos_selecionados: registro.alimentos
                });
                
                if (result && result.success) {
                    this.storageManager.markAsSynchronized(registro.id);
                    sincronizados++;
                } else {
                    erros++;
                }
            } catch (error) {
                erros++;
                console.error('Erro ao sincronizar registro:', error);
            }
        }
        
        this.storageManager.removesynchronized();
        
        if (sincronizados > 0) {
            this.messageManager.success(`✅ ${sincronizados} registro(s) sincronizado(s) com sucesso!${erros > 0 ? `\n⚠️ ${erros} erro(s) encontrado(s).` : ''}`);
            await this.carregarHistorico();
        } else if (erros > 0) {
            this.messageManager.error(`❌ Não foi possível sincronizar os registros (${erros} erro(s)).\nVerifique sua conexão e tente novamente.`);
        }
        
        this.verificarRegistrosLocais();
    }

    adicionarRegistroLocalAoHistorico(registro) {
        const container = this.elements.historicoContainer;
        const historicoAtual = container.innerHTML;
        
        if (historicoAtual.includes('Nenhum registro encontrado')) {
            container.innerHTML = '';
        }
        
        const dataFormatada = new Date(registro.data_diario + 'T00:00:00').toLocaleDateString('pt-BR');
        
        const novoRegistroHTML = `
            <div style="display: grid; gap: 15px; margin-bottom: 15px;">
                <div style="border: 2px solid #ff9800; border-radius: 8px; padding: 20px; background: #fff3e0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h3 style="margin: 0; color: #ff9800;">📅 ${dataFormatada} <span style="background: #ff9800; color: white; padding: 2px 8px; border-radius: 10px; font-size: 10px; margin-left: 10px;">LOCAL</span></h3>
                        <button onclick="diarioManager.removerRegistroLocal('${registro.id}')" 
                                style="background: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            🗑️ Remover
                        </button>
                    </div>
                    <p style="margin: 10px 0; color: #333; line-height: 1.5;">${registro.descricao_diario || 'Sem descrição'}</p>
                    ${registro.alimentos.length > 0 ? `
                        <div style="margin-top: 10px;">
                            <strong style="color: #ff9800;">Alimentos:</strong>
                            <ul style="margin: 5px 0 0 20px; color: #333;">
                                ${registro.alimentos.map(a => `<li>${a.descricao_alimento}</li>`).join('')}
                            </ul>
                        </div>
                    ` : '<p style="color: #666; font-style: italic;">Nenhum alimento selecionado.</p>'}
                    <p style="color: #666; font-size: 11px; margin: 10px 0 0 0; font-style: italic;">
                        *Salvo localmente - será sincronizado automaticamente
                    </p>
                </div>
            </div>
        `;
        
        container.innerHTML = novoRegistroHTML + container.innerHTML;
    }

    removerRegistroLocal(id) {
        if (confirm('Deseja remover este registro local?')) {
            if (this.storageManager.remove(id)) {
                this.carregarHistorico();
                this.verificarRegistrosLocais();
                this.messageManager.success('Registro local removido.');
            } else {
                this.messageManager.error('Erro ao remover registro local.');
            }
        }
    }
}

// Expor métodos globais para compatibilidade com HTML inline
window.diarioManager = null;
