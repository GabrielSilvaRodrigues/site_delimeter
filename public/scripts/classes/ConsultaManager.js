class ConsultaManager {
    constructor(idPaciente) {
        this.idPaciente = idPaciente;
        this.apiClient = new ApiClient();
        this.messageManager = new MessageManager();
        
        this.API_CONSULTAS = '/api/consultas';
        
        this.initializeElements();
        this.initializeEventListeners();
        this.carregarConsultas();
    }

    initializeElements() {
        this.elements = {
            form: document.getElementById('consultaForm'),
            consultasContainer: document.getElementById('consultasContainer')
        };
    }

    initializeEventListeners() {
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => this.agendarConsulta(e));
        }
    }

    async agendarConsulta(event) {
        event.preventDefault();

        const formData = new FormData(this.elements.form);
        const data = {
            data_consulta: formData.get('data_consulta'),
            tipo_profissional: formData.get('tipo_profissional'),
            id_paciente: this.idPaciente,
            observacoes: formData.get('observacoes') || null
        };

        // Validar data futura
        const dataConsulta = new Date(data.data_consulta);
        const agora = new Date();
        
        if (dataConsulta <= agora) {
            this.messageManager.error('A data da consulta deve ser futura');
            return;
        }

        try {
            const response = await this.apiClient.post(`${this.API_CONSULTAS}/criar`, data);

            if (response.success) {
                this.messageManager.success('Consulta agendada com sucesso!');
                this.elements.form.reset();
                this.carregarConsultas();
            } else {
                this.messageManager.error(response.error || 'Erro ao agendar consulta');
            }
        } catch (error) {
            console.error('Erro ao agendar consulta:', error);
            this.messageManager.error('Erro ao agendar consulta');
        }
    }

    async carregarConsultas() {
        try {
            // Usar nova rota de agenda
            const response = await this.apiClient.get(`${this.API_CONSULTAS}/buscar-agenda-por-paciente`, {
                id_paciente: this.idPaciente
            });

            if (response.success && response.data) {
                this.exibirConsultas(response.data);
            } else {
                this.elements.consultasContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma consulta agendada.</p>';
            }
        } catch (error) {
            console.error('Erro ao carregar consultas:', error);
            
            // Fallback para rota antiga
            try {
                const fallbackResponse = await this.apiClient.get(`${this.API_CONSULTAS}/buscar-por-paciente`, {
                    id_paciente: this.idPaciente
                });
                
                if (fallbackResponse.success && fallbackResponse.data) {
                    this.exibirConsultas(fallbackResponse.data);
                } else {
                    this.elements.consultasContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma consulta agendada.</p>';
                }
            } catch (fallbackError) {
                this.elements.consultasContainer.innerHTML = '<p style="text-align: center; color: #f44336; padding: 20px;">Erro ao carregar consultas.</p>';
            }
        }
    }

    exibirConsultas(consultas) {
        if (!consultas || consultas.length === 0) {
            this.elements.consultasContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma consulta agendada.</p>';
            return;
        }

        const html = consultas.map(consulta => {
            const dataFormatada = new Date(consulta.data_consulta).toLocaleString('pt-BR');
            const tipoConsulta = consulta.tipo_consulta || 'Não especificado';
            const status = consulta.status_agenda || 'agendado';
            const observacoes = consulta.observacoes || '';
            
            // Cores por status
            const statusColors = {
                'agendado': '#ff9800',
                'confirmado': '#2196f3',
                'realizado': '#4caf50',
                'cancelado': '#f44336'
            };
            
            const statusColor = statusColors[status] || '#666';
            
            return `
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 15px; background: #f9f9f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h4 style="margin: 0; color: #4caf50;">📅 ${dataFormatada}</h4>
                        <div style="display: flex; gap: 5px;">
                            ${status === 'agendado' ? `
                                <button onclick="consultaManager.atualizarStatus(${consulta.id_agenda || consulta.id_consulta}, 'confirmado')" 
                                        style="background: #2196f3; color: white; border: none; padding: 3px 8px; border-radius: 3px; cursor: pointer; font-size: 11px;">
                                    ✓ Confirmar
                                </button>
                            ` : ''}
                            <button onclick="consultaManager.cancelarConsulta(${consulta.id_agenda || consulta.id_consulta})" 
                                    style="background: #f44336; color: white; border: none; padding: 3px 8px; border-radius: 3px; cursor: pointer; font-size: 11px;">
                                ❌ Cancelar
                            </button>
                        </div>
                    </div>
                    <div style="display: grid; gap: 5px;">
                        <p style="margin: 0; color: #333;">
                            <strong>Tipo:</strong> ${tipoConsulta === 'medico' ? 'Consulta Médica' : 'Consulta Nutricional'}
                        </p>
                        <p style="margin: 0; color: #333;">
                            <strong>Status:</strong> 
                            <span style="background: ${statusColor}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                ${status.charAt(0).toUpperCase() + status.slice(1)}
                            </span>
                        </p>
                        ${observacoes ? `<p style="margin: 0; color: #666; font-style: italic;"><strong>Observações:</strong> ${observacoes}</p>` : ''}
                    </div>
                </div>
            `;
        }).join('');

        this.elements.consultasContainer.innerHTML = html;
    }

    async atualizarStatus(idAgenda, novoStatus) {
        try {
            const response = await this.apiClient.post(`${this.API_CONSULTAS}/atualizar-status-agenda`, {
                id_agenda: idAgenda,
                status: novoStatus
            });

            if (response.success) {
                this.messageManager.success('Status atualizado com sucesso!');
                this.carregarConsultas();
            } else {
                this.messageManager.error(response.error || 'Erro ao atualizar status');
            }
        } catch (error) {
            console.error('Erro ao atualizar status:', error);
            this.messageManager.error('Erro ao atualizar status');
        }
    }

    async cancelarConsulta(idConsulta) {
        if (!confirm('Tem certeza que deseja cancelar esta consulta?')) {
            return;
        }

        try {
            const response = await this.apiClient.delete(`${this.API_CONSULTAS}/deletar`, {
                id: idConsulta
            });

            if (response.success) {
                this.messageManager.success('Consulta cancelada com sucesso!');
                this.carregarConsultas();
            } else {
                this.messageManager.error(response.error || 'Erro ao cancelar consulta');
            }
        } catch (error) {
            console.error('Erro ao cancelar consulta:', error);
            this.messageManager.error('Erro ao cancelar consulta');
        }
    }
}
