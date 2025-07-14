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
            id_paciente: this.idPaciente
        };

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
            const response = await this.apiClient.get(`${this.API_CONSULTAS}/buscar-por-paciente`, {
                id_paciente: this.idPaciente
            });

            if (response.success && response.data) {
                this.exibirConsultas(response.data);
            } else {
                this.elements.consultasContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma consulta agendada.</p>';
            }
        } catch (error) {
            console.error('Erro ao carregar consultas:', error);
            this.elements.consultasContainer.innerHTML = '<p style="text-align: center; color: #f44336; padding: 20px;">Erro ao carregar consultas.</p>';
        }
    }

    exibirConsultas(consultas) {
        if (!consultas || consultas.length === 0) {
            this.elements.consultasContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma consulta agendada.</p>';
            return;
        }

        const html = consultas.map(consulta => {
            const dataFormatada = new Date(consulta.data_consulta).toLocaleString('pt-BR');
            
            return `
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 15px; background: #f9f9f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h4 style="margin: 0; color: #4caf50;">📅 ${dataFormatada}</h4>
                        <button onclick="consultaManager.cancelarConsulta(${consulta.id_consulta})" 
                                style="background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            ❌ Cancelar
                        </button>
                    </div>
                    <p style="margin: 0; color: #333;">
                        <strong>Status:</strong> Agendada
                    </p>
                </div>
            `;
        }).join('');

        this.elements.consultasContainer.innerHTML = html;
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
