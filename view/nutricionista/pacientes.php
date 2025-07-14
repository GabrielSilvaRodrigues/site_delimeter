<div class="nutricionista-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e8f5e9 100%); min-height: 100vh;">
    <main class="nutricionista-main-content" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        
        <!-- Header -->
        <div class="nutricionista-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #4caf50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="font-size:2.2rem; margin-bottom: 8px; color: #fff; text-shadow: 1px 2px 6px rgba(0,0,0,0.2);">
                👥 Gerenciamento de Pacientes
            </h1>
            <p style="font-size:1.1rem; color:#e8f5e9; margin: 0;">
                Acompanhe o progresso nutricional dos seus pacientes
            </p>
        </div>

        <!-- Mensagem de feedback -->
        <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>

        <!-- Filtros e Busca -->
        <section class="filtros-section" style="background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #4caf50; margin-bottom: 15px;">🔍 Buscar Pacientes</h3>
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: end;">
                <div>
                    <label for="buscar_paciente" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Buscar por nome ou CPF:</label>
                    <input type="text" id="buscar_paciente" placeholder="Digite o nome ou CPF do paciente..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <button id="limparBusca" style="background: #9e9e9e; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                    Limpar
                </button>
            </div>
        </section>

        <!-- Lista de Pacientes -->
        <section class="pacientes-section" style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #4caf50; margin-bottom: 20px; font-size: 1.4rem;">
                📋 Lista de Pacientes
            </h2>
            
            <div id="pacientesContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando pacientes...</p>
            </div>
        </section>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/nutricionista" style="color: #4caf50; text-decoration: none; font-weight: bold;">
                ← Voltar ao painel nutricionista
            </a>
        </div>
    </main>
</div>

<!-- Incluir scripts das classes -->
<script src="/public/scripts/classes/ApiClient.js"></script>
<script src="/public/scripts/classes/MessageManager.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiClient = new ApiClient();
    const messageManager = new MessageManager();
    
    const elements = {
        buscarInput: document.getElementById('buscar_paciente'),
        limparBtn: document.getElementById('limparBusca'),
        container: document.getElementById('pacientesContainer')
    };
    
    // Event listeners
    elements.buscarInput.addEventListener('input', function(e) {
        const termo = e.target.value.trim();
        if (termo.length >= 2) {
            buscarPacientes(termo);
        } else if (termo.length === 0) {
            carregarTodosPacientes();
        }
    });
    
    elements.limparBtn.addEventListener('click', function() {
        elements.buscarInput.value = '';
        carregarTodosPacientes();
    });
    
    async function carregarTodosPacientes() {
        try {
            const response = await apiClient.get('/api/nutricionista/pacientes');
            
            if (response.success && response.data) {
                exibirPacientes(response.data);
            } else {
                elements.container.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhum paciente encontrado.</p>';
            }
        } catch (error) {
            console.error('Erro ao carregar pacientes:', error);
            elements.container.innerHTML = '<p style="text-align: center; color: #f44336; padding: 20px;">Erro ao carregar lista de pacientes.</p>';
        }
    }
    
    async function buscarPacientes(termo) {
        try {
            const response = await apiClient.get('/api/nutricionista/pacientes/buscar', {
                termo: termo
            });
            
            if (response.success && response.data) {
                exibirPacientes(response.data);
            } else {
                elements.container.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhum paciente encontrado para a busca.</p>';
            }
        } catch (error) {
            console.error('Erro ao buscar pacientes:', error);
            messageManager.error('Erro ao buscar pacientes');
        }
    }
    
    function exibirPacientes(pacientes) {
        if (!pacientes || pacientes.length === 0) {
            elements.container.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhum paciente encontrado.</p>';
            return;
        }
        
        const html = pacientes.map(paciente => `
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 15px; background: #f9f9f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0; color: #4caf50;">👤 ${paciente.nome_usuario || 'Nome não informado'}</h3>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="verDiario(${paciente.id_paciente})" 
                                style="background: #ff9800; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                            📔 Diário
                        </button>
                        <button onclick="criarDieta(${paciente.id_paciente})" 
                                style="background: #4caf50; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                            🥗 Nova Dieta
                        </button>
                        <button onclick="verHistorico(${paciente.id_paciente})" 
                                style="background: #2196f3; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                            📈 Progresso
                        </button>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <strong>Email:</strong> ${paciente.email_usuario || 'Não informado'}
                    </div>
                    <div>
                        <strong>CPF:</strong> ${paciente.cpf || 'Não informado'}
                    </div>
                    <div>
                        <strong>NIS:</strong> ${paciente.nis || 'Não informado'}
                    </div>
                    <div>
                        <strong>Status:</strong> ${paciente.status_usuario ? 'Ativo' : 'Inativo'}
                    </div>
                </div>
                
                ${paciente.ultima_medida ? `
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                        <strong style="color: #4caf50;">Última Medição:</strong>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-top: 5px;">
                            <span>Peso: ${paciente.ultima_medida.peso_paciente || 'N/A'} kg</span>
                            <span>Altura: ${paciente.ultima_medida.altura_paciente || 'N/A'} m</span>
                            <span>IMC: ${calcularIMC(paciente.ultima_medida.peso_paciente, paciente.ultima_medida.altura_paciente)}</span>
                            <span>Data: ${paciente.ultima_medida.data_medida ? new Date(paciente.ultima_medida.data_medida).toLocaleDateString('pt-BR') : 'N/A'}</span>
                        </div>
                    </div>
                ` : ''}
            </div>
        `).join('');
        
        elements.container.innerHTML = html;
    }
    
    function calcularIMC(peso, altura) {
        if (!peso || !altura) return 'N/A';
        const imc = peso / (altura * altura);
        return imc.toFixed(1);
    }
    
    window.verDiario = function(idPaciente) {
        window.location.href = `/nutricionista/paciente/${idPaciente}/diario`;
    };
    
    window.criarDieta = function(idPaciente) {
        window.location.href = `/nutricionista/dieta/criar?paciente=${idPaciente}`;
    };
    
    window.verHistorico = function(idPaciente) {
        window.location.href = `/nutricionista/paciente/${idPaciente}/progresso`;
    };
    
    // Carregar pacientes na inicialização
    carregarTodosPacientes();
});
</script>
