<?php
// Inicializar sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: /usuario/login');
    exit;
}
?>

<div class="conta-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%); min-height: 100vh;">
    <main class="conta-main-content" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div class="conta-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #4CAF50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="color: #fff; margin: 0; font-size: 2rem;">
                ⚙️ Configurações da Conta
            </h1>
        </div>

        <div class="conta-form-container" style="background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 16px rgba(76,175,80,0.15);">
            <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>
            
            <!-- Formulário básico do usuário -->
            <form id="contaForm" method="POST" action="/conta/atualizar">
                <h3 style="color: #4caf50; margin-bottom: 15px; border-bottom: 2px solid #4caf50; padding-bottom: 5px;">
                    👤 Dados Básicos
                </h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="nome_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Nome completo:</label>
                    <input type="text" id="nome_usuario" name="nome_usuario" required 
                           value="<?php echo htmlspecialchars($_SESSION['usuario']['nome_usuario']); ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Email:</label>
                    <input type="email" id="email_usuario" name="email_usuario" required 
                           value="<?php echo htmlspecialchars($_SESSION['usuario']['email_usuario']); ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="senha_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Nova senha (deixe em branco para manter a atual):</label>
                    <input type="password" id="senha_usuario" name="senha_usuario" 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-bottom: 20px;">
                    💾 Salvar Dados Básicos
                </button>
            </form>

            <!-- Seção de Perfis Específicos -->
            <div style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;">
                <h3 style="color: #4caf50; margin-bottom: 15px; border-bottom: 2px solid #4caf50; padding-bottom: 5px;">
                    🎭 Perfis Específicos
                </h3>

                <!-- Seção Paciente -->
                <div id="pacienteSection" style="margin-bottom: 25px; padding: 20px; background: #e8f5e9; border-radius: 8px; border-left: 4px solid #4caf50;">
                    <h4 style="color: #2e7d32; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                        🧑‍🦱 Perfil de Paciente
                    </h4>
                    
                    <?php if (isset($_SESSION['usuario']['tipo']) && $_SESSION['usuario']['tipo'] === 'paciente'): ?>
                        <p style="color: #2e7d32; margin-bottom: 15px;">✅ Você já possui um perfil de paciente ativo.</p>
                        
                        <form id="pacienteForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CPF:</label>
                                <input type="text" id="cpf_paciente" name="cpf" placeholder="000.000.000-00" 
                                       value="<?php echo htmlspecialchars($_SESSION['usuario']['cpf'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">NIS:</label>
                                <input type="text" id="nis_paciente" name="nis" placeholder="000.00000.00-0"
                                       value="<?php echo htmlspecialchars($_SESSION['usuario']['nis'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        </form>
                        
                        <div style="display: flex; gap: 10px;">
                            <button onclick="atualizarPaciente()" style="flex: 1; padding: 10px; background: #4caf50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🔄 Atualizar Dados
                            </button>
                            <button onclick="excluirPerfil('paciente')" style="flex: 1; padding: 10px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🗑️ Excluir Perfil
                            </button>
                            <button onclick="sairPerfil('paciente')" style="flex: 1; padding: 10px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🚪 Sair do Perfil
                            </button>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; margin-bottom: 15px;">Você não possui um perfil de paciente.</p>
                        <button onclick="criarPerfil('paciente')" style="width: 100%; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            ➕ Criar Perfil de Paciente
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Seção Nutricionista -->
                <div id="nutricionistaSection" style="margin-bottom: 25px; padding: 20px; background: #e8f5e9; border-radius: 8px; border-left: 4px solid #43a047;">
                    <h4 style="color: #2e7d32; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                        🥗 Perfil de Nutricionista
                    </h4>
                    
                    <?php if (isset($_SESSION['usuario']['tipo']) && $_SESSION['usuario']['tipo'] === 'nutricionista'): ?>
                        <p style="color: #2e7d32; margin-bottom: 15px;">✅ Você já possui um perfil de nutricionista ativo.</p>
                        
                        <form id="nutricionistaForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CRN:</label>
                                <input type="text" id="crm_nutricionista" name="crm_nutricionista" placeholder="12345/SP"
                                       value="<?php echo htmlspecialchars($_SESSION['usuario']['crm_nutricionista'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CPF:</label>
                                <input type="text" id="cpf_nutricionista" name="cpf" placeholder="000.000.000-00"
                                       value="<?php echo htmlspecialchars($_SESSION['usuario']['cpf'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        </form>
                        
                        <div style="display: flex; gap: 10px;">
                            <button onclick="atualizarNutricionista()" style="flex: 1; padding: 10px; background: #43a047; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🔄 Atualizar Dados
                            </button>
                            <button onclick="excluirPerfil('nutricionista')" style="flex: 1; padding: 10px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🗑️ Excluir Perfil
                            </button>
                            <button onclick="sairPerfil('nutricionista')" style="flex: 1; padding: 10px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🚪 Sair do Perfil
                            </button>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; margin-bottom: 15px;">Você não possui um perfil de nutricionista.</p>
                        <button onclick="criarPerfil('nutricionista')" style="width: 100%; padding: 12px; background: #43a047; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            ➕ Criar Perfil de Nutricionista
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Seção Médico -->
                <div id="medicoSection" style="margin-bottom: 25px; padding: 20px; background: #e3f2fd; border-radius: 8px; border-left: 4px solid #1976d2;">
                    <h4 style="color: #1565c0; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                        🩺 Perfil de Médico
                    </h4>
                    
                    <?php if (isset($_SESSION['usuario']['tipo']) && $_SESSION['usuario']['tipo'] === 'medico'): ?>
                        <p style="color: #1565c0; margin-bottom: 15px;">✅ Você já possui um perfil de médico ativo.</p>
                        
                        <form id="medicoForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CRM:</label>
                                <input type="text" id="crm_medico" name="crm_medico" placeholder="12345/SP"
                                       value="<?php echo htmlspecialchars($_SESSION['usuario']['crm_medico'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CPF:</label>
                                <input type="text" id="cpf_medico" name="cpf" placeholder="000.000.000-00"
                                       value="<?php echo htmlspecialchars($_SESSION['usuario']['cpf'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        </form>
                        
                        <div style="display: flex; gap: 10px;">
                            <button onclick="atualizarMedico()" style="flex: 1; padding: 10px; background: #1976d2; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🔄 Atualizar Dados
                            </button>
                            <button onclick="excluirPerfil('medico')" style="flex: 1; padding: 10px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🗑️ Excluir Perfil
                            </button>
                            <button onclick="sairPerfil('medico')" style="flex: 1; padding: 10px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                🚪 Sair do Perfil
                            </button>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; margin-bottom: 15px;">Você não possui um perfil de médico.</p>
                        <button onclick="criarPerfil('medico')" style="width: 100%; padding: 12px; background: #1976d2; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            ➕ Criar Perfil de Médico
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ações Gerais -->
            <div style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;">
                <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <button onclick="confirmarExclusaoTotal()" style="flex: 1; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                        🗑️ Excluir Conta Completa
                    </button>
                    <button onclick="sairCompleto()" style="flex: 1; padding: 12px; background: #6c757d; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                        🚪 Sair do Sistema
                    </button>
                </div>
                
                <div style="text-align: center;">
                    <a href="/usuario" style="color: #4caf50; text-decoration: none; font-weight: bold;">
                        ← Voltar ao painel
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.getElementById('contaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/conta/atualizar', {
        method: 'POST',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Dados atualizados com sucesso!', 'success');
        } else {
            showMessage(data.error || 'Erro ao atualizar dados.', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showMessage('Erro de conexão. Tente novamente.', 'error');
    });
});

function confirmarExclusao() {
    if (confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')) {
        fetch('/conta/deletar', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Conta excluída com sucesso.');
                window.location.href = '/delimeter';
            } else {
                showMessage(data.error || 'Erro ao excluir conta.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showMessage('Erro de conexão. Tente novamente.', 'error');
        });
    }
}

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.style.display = 'block';
    messageDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
    messageDiv.style.color = type === 'success' ? '#155724' : '#721c24';
    messageDiv.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
}

// Máscaras para campos
document.addEventListener('DOMContentLoaded', function() {
    // Máscara CPF
    ['cpf_paciente', 'cpf_nutricionista', 'cpf_medico'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                    e.target.value = value;
                }
            });
        }
    });

    // Máscara NIS
    const nisElement = document.getElementById('nis_paciente');
    if (nisElement) {
        nisElement.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d{5})(\d{2})(\d{1})/, '$1.$2.$3-$4');
                e.target.value = value;
            }
        });
    }
});

// Funções para criar perfis
function criarPerfil(tipo) {
    window.location.href = `/${tipo}/cadastro`;
}

// Funções para atualizar perfis
function atualizarPaciente() {
    const cpf = document.getElementById('cpf_paciente').value.replace(/\D/g, '');
    const nis = document.getElementById('nis_paciente').value.replace(/\D/g, '');
    
    if (!cpf || cpf.length !== 11) {
        showMessage('CPF deve ter 11 dígitos.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('cpf', cpf);
    if (nis) formData.append('nis', nis);
    formData.append('tipo_form', 'paciente');
    
    atualizarPerfil('paciente', formData);
}

function atualizarNutricionista() {
    const crn = document.getElementById('crm_nutricionista').value;
    const cpf = document.getElementById('cpf_nutricionista').value.replace(/\D/g, '');
    
    if (!crn || !cpf || cpf.length !== 11) {
        showMessage('CRN e CPF são obrigatórios.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('crm_nutricionista', crn);
    formData.append('cpf', cpf);
    formData.append('tipo_form', 'nutricionista');
    
    atualizarPerfil('nutricionista', formData);
}

function atualizarMedico() {
    const crm = document.getElementById('crm_medico').value;
    const cpf = document.getElementById('cpf_medico').value.replace(/\D/g, '');
    
    if (!crm || !cpf || cpf.length !== 11) {
        showMessage('CRM e CPF são obrigatórios.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('crm_medico', crm);
    formData.append('cpf', cpf);
    formData.append('tipo_form', 'medico');
    
    atualizarPerfil('medico', formData);
}

function atualizarPerfil(tipo, formData) {
    fetch(`/${tipo}/conta/atualizar`, {
        method: 'POST',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(`Dados do ${tipo} atualizados com sucesso!`, 'success');
        } else {
            showMessage(data.error || `Erro ao atualizar dados do ${tipo}.`, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showMessage('Erro de conexão. Tente novamente.', 'error');
    });
}

// Funções para excluir perfis
function excluirPerfil(tipo) {
    if (confirm(`Tem certeza que deseja excluir seu perfil de ${tipo}? Esta ação não pode ser desfeita.`)) {
        fetch(`/${tipo}/conta/deletar`, {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(`Perfil de ${tipo} excluído com sucesso!`, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showMessage(data.error || `Erro ao excluir perfil de ${tipo}.`, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showMessage('Erro de conexão. Tente novamente.', 'error');
        });
    }
}

// Funções para sair de perfis
function sairPerfil(tipo) {
    if (confirm(`Tem certeza que deseja sair do perfil de ${tipo}?`)) {
        fetch(`/${tipo}/conta/sair`, {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(`Saiu do perfil de ${tipo} com sucesso!`, 'success');
                setTimeout(() => {
                    window.location.href = '/usuario';
                }, 1500);
            } else {
                showMessage(data.error || `Erro ao sair do perfil de ${tipo}.`, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showMessage('Erro de conexão. Tente novamente.', 'error');
        });
    }
}

function confirmarExclusaoTotal() {
    if (confirm('Tem certeza que deseja excluir sua conta COMPLETA? Todos os seus perfis e dados serão perdidos. Esta ação não pode ser desfeita.')) {
        fetch('/conta/deletar', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Conta excluída com sucesso.');
                window.location.href = '/delimeter';
            } else {
                showMessage(data.error || 'Erro ao excluir conta.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showMessage('Erro de conexão. Tente novamente.', 'error');
        });
    }
}

function sairCompleto() {
    if (confirm('Tem certeza que deseja sair do sistema?')) {
        window.location.href = '/conta/sair';
    }
}
</script>