class PerfilManager {
    constructor(apiClient, messageManager, tipo) {
        this.apiClient = apiClient;
        this.messageManager = messageManager;
        this.tipo = tipo;
        this.existe = false;
        this.dados = null;
    }

    setupEventListeners() {
        const btnCriar = document.getElementById(`btn-criar-${this.tipo}`);
        const btnAtualizar = document.getElementById(`btn-atualizar-${this.tipo}`);
        const btnExcluir = document.getElementById(`btn-excluir-${this.tipo}`);
        const btnSair = document.getElementById(`btn-sair-${this.tipo}`);

        if (btnCriar) {
            btnCriar.addEventListener('click', () => this.criar());
        }
        if (btnAtualizar) {
            btnAtualizar.addEventListener('click', () => this.atualizar());
        }
        if (btnExcluir) {
            btnExcluir.addEventListener('click', () => this.confirmarExclusao());
        }
        if (btnSair) {
            btnSair.addEventListener('click', () => this.sair());
        }
    }

    async verificarExistencia() {
        try {
            const response = await this.apiClient.request(`/api/${this.tipo}/dados`);
            
            if (response.error) {
                this.existe = false;
                this.atualizarInterface(false);
            } else {
                this.existe = true;
                this.dados = response;
                this.atualizarInterface(true, response);
            }
        } catch (error) {
            console.error(`Erro ao verificar ${this.tipo}:`, error);
            this.existe = false;
            this.atualizarInterface(false);
        }
    }

    atualizarInterface(existe, dados = null) {
        const secao = document.getElementById(`perfil-${this.tipo}`);
        if (!secao) return;

        if (existe && dados) {
            this.mostrarPerfilExistente(secao, dados);
        } else {
            this.mostrarFormularioCriacao(secao);
        }
    }

    mostrarPerfilExistente(secao, dados) {
        const form = secao.querySelector('.perfil-form');
        if (form) {
            this.preencherFormulario(form, dados);
        }

        const btnCriar = secao.querySelector(`#btn-criar-${this.tipo}`);
        const btnsExistente = secao.querySelectorAll(`#btn-atualizar-${this.tipo}, #btn-excluir-${this.tipo}, #btn-sair-${this.tipo}`);

        if (btnCriar) btnCriar.style.display = 'none';
        btnsExistente.forEach(btn => {
            if (btn) btn.style.display = 'inline-block';
        });
    }

    mostrarFormularioCriacao(secao) {
        const btnCriar = secao.querySelector(`#btn-criar-${this.tipo}`);
        const btnsExistente = secao.querySelectorAll(`#btn-atualizar-${this.tipo}, #btn-excluir-${this.tipo}, #btn-sair-${this.tipo}`);

        if (btnCriar) btnCriar.style.display = 'inline-block';
        btnsExistente.forEach(btn => {
            if (btn) btn.style.display = 'none';
        });
    }

    preencherFormulario(form, dados) {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (dados[input.name]) {
                input.value = dados[input.name];
            }
        });
    }

    async criar() {
        const form = document.getElementById(`form-${this.tipo}`);
        if (!form) return;

        const formData = new FormData(form);
        const dados = Object.fromEntries(formData.entries());

        if (!this.validarFormulario(dados)) {
            return;
        }

        try {
            const response = await this.apiClient.post(`/api/${this.tipo}/criar`, dados);
            
            if (response.success) {
                this.messageManager.success(`Perfil de ${this.tipo} criado com sucesso!`);
                await this.verificarExistencia();
                
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                }
            } else {
                this.messageManager.error(response.error || `Erro ao criar perfil de ${this.tipo}.`);
            }
        } catch (error) {
            console.error(`Erro ao criar ${this.tipo}:`, error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }

    async atualizar() {
        const form = document.getElementById(`form-${this.tipo}`);
        if (!form) return;

        const formData = new FormData(form);
        const dados = Object.fromEntries(formData.entries());

        if (!this.validarFormulario(dados)) {
            return;
        }

        try {
            const response = await this.apiClient.put(`/api/${this.tipo}/atualizar`, dados);
            
            if (response.success) {
                this.messageManager.success(`Dados de ${this.tipo} atualizados com sucesso!`);
                await this.verificarExistencia();
            } else {
                this.messageManager.error(response.error || `Erro ao atualizar dados de ${this.tipo}.`);
            }
        } catch (error) {
            console.error(`Erro ao atualizar ${this.tipo}:`, error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }

    confirmarExclusao() {
        if (confirm(`Tem certeza que deseja excluir seu perfil de ${this.tipo}?`)) {
            this.excluir();
        }
    }

    async excluir() {
        try {
            const response = await this.apiClient.delete(`/api/${this.tipo}/excluir`);
            
            if (response.success) {
                this.messageManager.success(`Perfil de ${this.tipo} excluído com sucesso!`);
                await this.verificarExistencia();
                
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                }
            } else {
                this.messageManager.error(response.error || `Erro ao excluir perfil de ${this.tipo}.`);
            }
        } catch (error) {
            console.error(`Erro ao excluir ${this.tipo}:`, error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }

    async sair() {
        if (confirm(`Deseja sair do perfil de ${this.tipo}?`)) {
            try {
                const response = await this.apiClient.request(`/${this.tipo}/conta/sair`);
                
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    window.location.href = '/usuario';
                }
            } catch (error) {
                console.error(`Erro ao sair do perfil ${this.tipo}:`, error);
                window.location.href = '/usuario';
            }
        }
    }

    validarFormulario(dados) {
        // Implementação básica - será sobrescrita pelas classes filhas
        return true;
    }
}

// Classes específicas para cada perfil
class PacientePerfilManager extends PerfilManager {
    constructor(apiClient, messageManager) {
        super(apiClient, messageManager, 'paciente');
    }

    validarFormulario(dados) {
        if (!dados.cpf) {
            this.messageManager.error('CPF é obrigatório para pacientes.');
            return false;
        }
        return true;
    }
}

class NutricionistaPerfilManager extends PerfilManager {
    constructor(apiClient, messageManager) {
        super(apiClient, messageManager, 'nutricionista');
    }

    validarFormulario(dados) {
        if (!dados.crm_nutricionista) {
            this.messageManager.error('CRN é obrigatório para nutricionistas.');
            return false;
        }
        if (!dados.cpf) {
            this.messageManager.error('CPF é obrigatório para nutricionistas.');
            return false;
        }
        return true;
    }
}

class MedicoPerfilManager extends PerfilManager {
    constructor(apiClient, messageManager) {
        super(apiClient, messageManager, 'medico');
    }

    validarFormulario(dados) {
        if (!dados.crm_medico) {
            this.messageManager.error('CRM é obrigatório para médicos.');
            return false;
        }
        if (!dados.cpf) {
            this.messageManager.error('CPF é obrigatório para médicos.');
            return false;
        }
        return true;
    }
}
