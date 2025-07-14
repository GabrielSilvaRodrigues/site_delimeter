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

// Carregar dados específicos dos perfis se não estiverem na sessão
$dadosPaciente = null;
$dadosNutricionista = null;
$dadosMedico = null;

if (!empty($_SESSION['usuario']['id_usuario'])) {
    require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
    
    try {
        // Buscar dados do paciente
        $pacienteRepo = new \Htdocs\Src\Models\Repository\PacienteRepository();
        $dadosPaciente = $pacienteRepo->findByUsuarioId($_SESSION['usuario']['id_usuario']);
        
        // Buscar dados do nutricionista
        $nutricionistaRepo = new \Htdocs\Src\Models\Repository\NutricionistaRepository();
        $dadosNutricionista = $nutricionistaRepo->findByUsuarioId($_SESSION['usuario']['id_usuario']);
        
        // Buscar dados do médico
        $medicoRepo = new \Htdocs\Src\Models\Repository\MedicoRepository();
        $dadosMedico = $medicoRepo->findByUsuarioId($_SESSION['usuario']['id_usuario']);
        
    } catch (\Exception $e) {
        error_log("Erro ao carregar dados dos perfis: " . $e->getMessage());
        // Em caso de erro, definir como null para evitar crashes
        $dadosPaciente = null;
        $dadosNutricionista = null;
        $dadosMedico = null;
    }
}

// Exibir mensagens de feedback
$mensagem = '';
$tipoMensagem = '';
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $tipoMensagem = $_SESSION['tipo_mensagem'] ?? 'info';
    unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
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
            
            <?php if ($mensagem): ?>
                <div style="display: block; margin-bottom: 15px; padding: 10px; border-radius: 5px; 
                    <?php echo $tipoMensagem === 'success' ? 'background-color: #d4edda; color: #155724; border-left: 4px solid #28a745;' : 
                              ($tipoMensagem === 'error' ? 'background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545;' : 
                               'background-color: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8;'); ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>
            
            <!-- Formulário básico do usuário -->
            <form method="POST" action="/conta/atualizar">
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
                <div style="margin-bottom: 25px; padding: 20px; background: #e8f5e9; border-radius: 8px; border-left: 4px solid #4caf50;">
                    <h4 style="color: #2e7d32; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                        🧑‍🦱 Perfil de Paciente
                    </h4>
                    
                    <?php if ($dadosPaciente): ?>
                        <p style="color: #2e7d32; margin-bottom: 15px;">✅ Você já possui um perfil de paciente ativo.</p>
                        
                        <form method="POST" action="/paciente/conta/atualizar" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CPF:</label>
                                <input type="text" name="cpf" placeholder="000.000.000-00" 
                                       value="<?php echo htmlspecialchars($dadosPaciente['cpf'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">NIS:</label>
                                <input type="text" name="nis" placeholder="000.00000.00-0"
                                       value="<?php echo htmlspecialchars($dadosPaciente['nis'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div style="grid-column: 1 / -1;">
                                <button type="submit" style="width: 100%; padding: 10px; background: #4caf50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    🔄 Atualizar Dados
                                </button>
                            </div>
                        </form>
                        
                        <div style="display: flex; gap: 10px;">
                            <form method="POST" action="/paciente/conta/deletar" style="flex: 1;" onsubmit="return confirm('Tem certeza que deseja excluir seu perfil de paciente?')">
                                <button type="submit" style="width: 100%; padding: 10px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    🗑️ Excluir Perfil
                                </button>
                            </form>
                            <a href="/paciente/conta/sair" style="flex: 1; padding: 10px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: block; text-align: center;">
                                🚪 Sair do Perfil
                            </a>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; margin-bottom: 15px;">Você não possui um perfil de paciente.</p>
                        <a href="/paciente/cadastro" style="width: 100%; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: block; text-align: center;">
                            ➕ Criar Perfil de Paciente
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Seção Nutricionista -->
                <div style="margin-bottom: 25px; padding: 20px; background: #e8f5e9; border-radius: 8px; border-left: 4px solid #43a047;">
                    <h4 style="color: #2e7d32; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                        🥗 Perfil de Nutricionista
                    </h4>
                    
                    <?php if ($dadosNutricionista): ?>
                        <p style="color: #2e7d32; margin-bottom: 15px;">✅ Você já possui um perfil de nutricionista ativo.</p>
                        
                        <form method="POST" action="/nutricionista/conta/atualizar" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CRN:</label>
                                <input type="text" name="crm_nutricionista" placeholder="12345/SP"
                                       value="<?php echo htmlspecialchars($dadosNutricionista['crm_nutricionista'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CPF:</label>
                                <input type="text" name="cpf" placeholder="000.000.000-00"
                                       value="<?php echo htmlspecialchars($dadosNutricionista['cpf'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div style="grid-column: 1 / -1;">
                                <button type="submit" style="width: 100%; padding: 10px; background: #43a047; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    🔄 Atualizar Dados
                                </button>
                            </div>
                        </form>
                        
                        <div style="display: flex; gap: 10px;">
                            <form method="POST" action="/nutricionista/conta/deletar" style="flex: 1;" onsubmit="return confirm('Tem certeza que deseja excluir seu perfil de nutricionista?')">
                                <button type="submit" style="width: 100%; padding: 10px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    🗑️ Excluir Perfil
                                </button>
                            </form>
                            <a href="/nutricionista/conta/sair" style="flex: 1; padding: 10px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: block; text-align: center;">
                                🚪 Sair do Perfil
                            </a>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; margin-bottom: 15px;">Você não possui um perfil de nutricionista.</p>
                        <a href="/nutricionista/cadastro" style="width: 100%; padding: 12px; background: #43a047; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: block; text-align: center;">
                            ➕ Criar Perfil de Nutricionista
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Seção Médico -->
                <div style="margin-bottom: 25px; padding: 20px; background: #e3f2fd; border-radius: 8px; border-left: 4px solid #1976d2;">
                    <h4 style="color: #1565c0; margin: 0 0 15px 0; display: flex; align-items: center; gap: 8px;">
                        🩺 Perfil de Médico
                    </h4>
                    
                    <?php if ($dadosMedico): ?>
                        <p style="color: #1565c0; margin-bottom: 15px;">✅ Você já possui um perfil de médico ativo.</p>
                        
                        <form method="POST" action="/medico/conta/atualizar" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CRM:</label>
                                <input type="text" name="crm_medico" placeholder="12345/SP"
                                       value="<?php echo htmlspecialchars($dadosMedico['crm_medico'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">CPF:</label>
                                <input type="text" name="cpf" placeholder="000.000.000-00"
                                       value="<?php echo htmlspecialchars($dadosMedico['cpf'] ?? ''); ?>"
                                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div style="grid-column: 1 / -1;">
                                <button type="submit" style="width: 100%; padding: 10px; background: #1976d2; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    🔄 Atualizar Dados
                                </button>
                            </div>
                        </form>
                        
                        <div style="display: flex; gap: 10px;">
                            <form method="POST" action="/medico/conta/deletar" style="flex: 1;" onsubmit="return confirm('Tem certeza que deseja excluir seu perfil de médico?')">
                                <button type="submit" style="width: 100%; padding: 10px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                    🗑️ Excluir Perfil
                                </button>
                            </form>
                            <a href="/medico/conta/sair" style="flex: 1; padding: 10px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: block; text-align: center;">
                                🚪 Sair do Perfil
                            </a>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; margin-bottom: 15px;">Você não possui um perfil de médico.</p>
                        <a href="/medico/cadastro" style="width: 100%; padding: 12px; background: #1976d2; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: block; text-align: center;">
                            ➕ Criar Perfil de Médico
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ações Gerais -->
            <div style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;">
                <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <form method="POST" action="/conta/deletar" style="flex: 1;" onsubmit="return confirm('Tem certeza que deseja excluir sua conta completa? Esta ação não pode ser desfeita!')">
                        <button type="submit" style="width: 100%; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                            🗑️ Excluir Conta Completa
                        </button>
                    </form>
                    <a href="/conta/sair" style="flex: 1; padding: 12px; background: #6c757d; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; text-decoration: none; text-align: center; display: block;">
                        🚪 Sair do Sistema
                    </a>
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