class DadosAntropometricosManager {
    constructor(idPaciente) {
        this.idPaciente = idPaciente;
        this.apiClient = new ApiClient();
        this.messageManager = new MessageManager();
        
        this.API_BASE = '/api/dados-antropometricos';
        
        this.initializeElements();
        this.initializeEventListeners();
        this.carregarHistorico();
    }

    initializeElements() {
        this.elements = {
            form: document.getElementById('dadosForm'),
            historicoContainer: document.getElementById('historicoContainer'),
            imcResult: document.getElementById('imcResult'),
            imcValue: document.getElementById('imcValue'),
            imcClassification: document.getElementById('imcClassification')
        };
    }

    initializeEventListeners() {
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        data.id_paciente = this.idPaciente;

        try {
            const response = await this.apiClient.post(`${this.API_BASE}/criar`, data);
            
            if (response.success) {
                this.messageManager.success('Dados antropométricos salvos com sucesso!');
                this.elements.form.reset();
                document.getElementById('data_medida').value = new Date().toISOString().split('T')[0];
                if (this.elements.imcResult) {
                    this.elements.imcResult.style.display = 'none';
                }
                this.carregarHistorico();
            } else {
                this.messageManager.error(response.error || 'Erro ao salvar dados');
            }
        } catch (error) {
            console.error('Erro ao salvar dados:', error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }

    async carregarHistorico() {
        if (!this.elements.historicoContainer) return;
        
        this.elements.historicoContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">🔄 Carregando histórico...</p>';
        
        try {
            const response = await this.apiClient.get(`${this.API_BASE}/buscar-por-paciente`, {
                id_paciente: this.idPaciente
            });
            
            let dados = [];
            if (response.success && response.data) {
                dados = response.data;
            } else if (Array.isArray(response)) {
                dados = response;
            }
            
            if (dados.length > 0) {
                this.renderizarTabelaHistorico(dados);
            } else {
                this.mostrarHistoricoVazio();
            }
            
        } catch (error) {
            console.error('Erro ao carregar histórico:', error);
            this.mostrarErroHistorico(error);
        }
    }

    renderizarTabelaHistorico(dados) {
        let html = '<div style="overflow-x: auto;"><table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
        html += '<thead><tr style="background: #4caf50; color: white;">';
        html += '<th style="padding: 12px; text-align: left;">Data</th>';
        html += '<th style="padding: 12px; text-align: left;">Sexo</th>';
        html += '<th style="padding: 12px; text-align: left;">Altura (m)</th>';
        html += '<th style="padding: 12px; text-align: left;">Peso (kg)</th>';
        html += '<th style="padding: 12px; text-align: left;">IMC</th>';
        html += '<th style="padding: 12px; text-align: left;">Classificação</th>';
        html += '<th style="padding: 12px; text-align: center;">Ações</th>';
        html += '</tr></thead><tbody>';
        
        dados.forEach(item => {
            const altura = parseFloat(item.altura_paciente);
            const peso = parseFloat(item.peso_paciente);
            const imc = (altura && peso && altura > 0) ? (peso / (altura * altura)).toFixed(2) : '-';
            
            let sexo = '-';
            if (item.sexo_paciente === 0 || item.sexo_paciente === '0') sexo = 'Feminino';
            else if (item.sexo_paciente === 1 || item.sexo_paciente === '1') sexo = 'Masculino';
            
            let classificacao = '-';
            if (imc !== '-') {
                const imcNum = parseFloat(imc);
                if (imcNum < 18.5) classificacao = 'Abaixo do peso';
                else if (imcNum < 25) classificacao = 'Peso normal';
                else if (imcNum < 30) classificacao = 'Sobrepeso';
                else classificacao = 'Obesidade';
            }
            
            const dataFormatada = item.data_medida ? 
                new Date(item.data_medida + 'T00:00:00').toLocaleDateString('pt-BR') : '-';
            
            html += `<tr style="border-bottom: 1px solid #eee;">`;
            html += `<td style="padding: 10px;">${dataFormatada}</td>`;
            html += `<td style="padding: 10px;">${sexo}</td>`;
            html += `<td style="padding: 10px;">${item.altura_paciente || '-'}</td>`;
            html += `<td style="padding: 10px;">${item.peso_paciente || '-'}</td>`;
            html += `<td style="padding: 10px; font-weight: bold;">${imc}</td>`;
            html += `<td style="padding: 10px;">${classificacao}</td>`;
            html += `<td style="padding: 10px; text-align: center;">`;
            
            const id = item.id_dados_antropometricos || item.id_medida;
            if (id) {
                html += `<button onclick="dadosManager.excluirMedida(${id})" style="background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">🗑️ Excluir</button>`;
            }
            html += `</td></tr>`;
        });
        
        html += '</tbody></table></div>';
        html += `<p style="text-align: center; color: #666; font-size: 12px; margin-top: 10px;">
            Total de ${dados.length} medida${dados.length !== 1 ? 's' : ''} encontrada${dados.length !== 1 ? 's' : ''}
        </p>`;
        
        this.elements.historicoContainer.innerHTML = html;
    }

    mostrarHistoricoVazio() {
        this.elements.historicoContainer.innerHTML = `
            <div style="text-align: center; padding: 20px; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px;">
                <h3 style="color: #1976d2;">📊 Nenhuma medida encontrada</h3>
                <p style="color: #1976d2; margin: 10px 0;">Adicione sua primeira medida usando o formulário acima!</p>
                <button onclick="dadosManager.carregarHistorico()" style="background: #2196f3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                    🔄 Atualizar
                </button>
            </div>
        `;
    }

    mostrarErroHistorico(error) {
        this.elements.historicoContainer.innerHTML = `
            <div style="text-align: center; padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px;">
                <h3 style="color: #721c24;">❌ Erro ao carregar histórico</h3>
                <p style="color: #721c24; margin: 10px 0; font-size: 14px;">${error.message}</p>
                <div style="margin-top: 15px;">
                    <button onclick="dadosManager.carregarHistorico()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                        🔄 Tentar Novamente
                    </button>
                </div>
            </div>
        `;
    }

    async calcularIMC() {
        const altura = document.getElementById('altura_paciente').value;
        const peso = document.getElementById('peso_paciente').value;
        
        if (!altura || !peso) {
            this.messageManager.warning('Por favor, preencha altura e peso para calcular o IMC.');
            return;
        }
        
        try {
            const response = await this.apiClient.get(`${this.API_BASE}/calcular-imc`, {
                altura: altura,
                peso: peso
            });
            
            if (response.success && response.data) {
                const { imc, classificacao } = response.data;
                
                if (this.elements.imcValue) {
                    this.elements.imcValue.textContent = `IMC: ${Number(imc).toFixed(2)}`;
                }
                if (this.elements.imcClassification) {
                    this.elements.imcClassification.textContent = `Classificação: ${classificacao}`;
                }
                if (this.elements.imcResult) {
                    this.elements.imcResult.style.display = 'block';
                }
            } else {
                this.messageManager.error(response.error || 'Erro ao calcular IMC');
            }
        } catch (error) {
            console.error('Erro ao calcular IMC:', error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }

    async excluirMedida(id) {
        if (!confirm('Tem certeza que deseja excluir esta medida?')) {
            return;
        }
        
        try {
            const response = await this.apiClient.post(`${this.API_BASE}/deletar`, {
                id: id
            });
            
            if (response.success) {
                this.messageManager.success('Medida excluída com sucesso!');
                this.carregarHistorico();
            } else {
                this.messageManager.error(response.error || 'Erro ao excluir medida');
            }
        } catch (error) {
            console.error('Erro ao excluir medida:', error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }
}

// Expor métodos globais para compatibilidade
window.dadosManager = null;
