class ContaManager {
    constructor() {
        this.apiClient = new ApiClient();
        this.messageManager = new MessageManager();
        this.perfilManagers = {
            paciente: new PacientePerfilManager(this.apiClient, this.messageManager),
            nutricionista: new NutricionistaPerfilManager(this.apiClient, this.messageManager),
            medico: new MedicoPerfilManager(this.apiClient, this.messageManager)
        };
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.carregarDadosUsuario();
        this.verificarPerfisExistentes();
    }

    setupEventListeners() {
        // Botão de atualizar dados básicos
        const btnAtualizarUsuario = document.getElementById('btn-atualizar-usuario');
        if (btnAtualizarUsuario) {
            btnAtualizarUsuario.addEventListener('click', () => this.atualizarUsuario());
        }

        // Botão de excluir conta
        const btnExcluirConta = document.getElementById('btn-excluir-conta');
        if (btnExcluirConta) {
            btnExcluirConta.addEventListener('click', () => this.confirmarExclusao());
        }

        // Botão de sair
        const btnSair = document.getElementById('btn-sair');
        if (btnSair) {
            btnSair.addEventListener('click', () => this.sairConta());
        }

        // Event listeners para perfis específicos
        Object.keys(this.perfilManagers).forEach(tipo => {
            this.perfilManagers[tipo].setupEventListeners();
        });
    }

    async carregarDadosUsuario() {
        try {
            const response = await this.apiClient.request('/api/usuario/dados');
            if (response.error) {
                this.messageManager.error(response.error);
                return;
            }

            this.preencherFormularioUsuario(response);
        } catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
            this.messageManager.error('Erro ao carregar dados do usuário.');
        }
    }

    preencherFormularioUsuario(dados) {
        const campos = ['nome_usuario', 'email_usuario'];
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            if (elemento && dados[campo]) {
                elemento.value = dados[campo];
            }
        });
    }

    async atualizarUsuario() {
        const form = document.getElementById('form-usuario');
        if (!form) return;

        const formData = new FormData(form);
        const dados = Object.fromEntries(formData.entries());

        // Validação básica
        if (!dados.nome_usuario || !dados.email_usuario) {
            this.messageManager.error('Nome e email são obrigatórios.');
            return;
        }

        try {
            const response = await this.apiClient.put('/api/usuario/atualizar', dados);
            
            if (response.success) {
                this.messageManager.success('Dados atualizados com sucesso!');
            } else {
                this.messageManager.error(response.error || 'Erro ao atualizar dados.');
            }
        } catch (error) {
            console.error('Erro ao atualizar usuário:', error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }

    async verificarPerfisExistentes() {
        for (const tipo of Object.keys(this.perfilManagers)) {
            await this.perfilManagers[tipo].verificarExistencia();
        }
    }

    confirmarExclusao() {
        if (confirm('ATENÇÃO: Esta ação excluirá sua conta permanentemente. Deseja continuar?')) {
            if (confirm('Última confirmação: Tem ABSOLUTA certeza?')) {
                this.excluirConta();
            }
        }
    }

    async excluirConta() {
        try {
            const response = await this.apiClient.delete('/api/usuario/excluir');
            
            if (response.success) {
                this.messageManager.success('Conta excluída com sucesso.');
                setTimeout(() => {
                    window.location.href = response.redirect || '/';
                }, 2000);
            } else {
                this.messageManager.error(response.error || 'Erro ao excluir conta.');
            }
        } catch (error) {
            console.error('Erro ao excluir conta:', error);
            this.messageManager.error('Erro de conexão. Tente novamente.');
        }
    }

    async sairConta() {
        if (confirm('Deseja realmente sair do sistema?')) {
            try {
                await this.apiClient.request('/usuario/conta/sair');
                window.location.href = '/';
            } catch (error) {
                console.error('Erro ao sair:', error);
                window.location.href = '/';
            }
        }
    }
}
