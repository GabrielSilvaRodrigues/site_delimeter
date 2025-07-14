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

// Garantir que temos os dados do paciente
if (empty($_SESSION['paciente']['id_paciente'])) {
    // Se não temos na sessão, tentar buscar no banco
    if (!empty($_SESSION['usuario']['id_usuario'])) {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        $pacienteRepo = new \Htdocs\Src\Models\Repository\PacienteRepository();
        $pacienteData = $pacienteRepo->findByUsuarioId($_SESSION['usuario']['id_usuario']);
        if ($pacienteData) {
            $_SESSION['paciente'] = $pacienteData;
        } else {
            header('Location: /paciente/cadastro');
            exit;
        }
    } else {
        header('Location: /paciente');
        exit;
    }
}

$id_paciente = $_SESSION['paciente']['id_paciente'];
?>

<div class="dados-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e8f5e8 100%); min-height: 100vh;">
    <main class="dados-main-content" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        
        <!-- Header -->
        <div class="dados-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #4caf50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="font-size:2.2rem; margin-bottom: 8px; color: #fff; text-shadow: 1px 2px 6px rgba(0,0,0,0.2);">
                📊 Dados Antropométricos
            </h1>
            <p style="font-size:1.1rem; color:#e8f5e8; margin: 0;">
                Acompanhe suas medidas corporais e cálculo do IMC
            </p>
            <?php if ($sexoAtual !== '' || $alturaAtual || $pesoAtual): ?>
                <div style="margin-top: 15px; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 8px;">
                    <h3 style="color: #fff; margin: 0 0 10px 0; font-size: 1.1rem;">📈 Últimos Dados Registrados:</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; color: #e8f5e8;">
                        <?php if ($sexoAtual !== ''): ?>
                            <span><strong>Sexo:</strong> <?php echo $sexoAtual == '1' ? 'Masculino' : 'Feminino'; ?></span>
                        <?php endif; ?>
                        <?php if ($alturaAtual): ?>
                            <span><strong>Altura:</strong> <?php echo htmlspecialchars($alturaAtual); ?>m</span>
                        <?php endif; ?>
                        <?php if ($pesoAtual): ?>
                            <span><strong>Peso:</strong> <?php echo htmlspecialchars($pesoAtual); ?>kg</span>
                        <?php endif; ?>
                        <?php if ($imcAtual): ?>
                            <span><strong>IMC:</strong> <?php echo number_format($imcAtual, 2); ?> (<?php echo htmlspecialchars($classificacaoAtual); ?>)</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Formulário de Nova Medida -->
        <section class="form-section" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #4caf50; margin-bottom: 20px; font-size: 1.4rem;">
                ➕ Nova Medida
            </h2>
            <form id="dadosForm" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div class="form-group">
                    <label for="sexo_paciente" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Sexo:</label>
                    <select id="sexo_paciente" name="sexo_paciente" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        <option value="">Selecione</option>
                        <option value="0" <?php echo $sexoAtual === '0' ? 'selected' : ''; ?>>Feminino</option>
                        <option value="1" <?php echo $sexoAtual === '1' ? 'selected' : ''; ?>>Masculino</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="altura_paciente" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Altura (m):</label>
                    <input type="number" id="altura_paciente" name="altura_paciente" step="0.01" min="0.5" max="2.5" placeholder="Ex: 1.75" value="<?php echo htmlspecialchars($alturaAtual); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label for="peso_paciente" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Peso (kg):</label>
                    <input type="number" id="peso_paciente" name="peso_paciente" step="0.1" min="20" max="300" placeholder="Ex: 70.5" value="<?php echo htmlspecialchars($pesoAtual); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label for="data_medida" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Data da Medida:</label>
                    <input type="date" id="data_medida" name="data_medida" value="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group" style="grid-column: 1 / -1; display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                    <button type="button" onclick="calcularIMC()" style="background: #ff9800; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        🧮 Calcular IMC
                    </button>
                    <button type="submit" style="background: #4caf50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        💾 Salvar Medida
                    </button>
                </div>
            </form>
            <!-- Resultado do IMC -->
            <div id="imcResult" style="margin-top: 15px; padding: 15px; background: #f5f5f5; border-radius: 5px; display: none;">
                <h3 style="color: #4caf50; margin: 0 0 10px 0;">Resultado do IMC:</h3>
                <p id="imcValue" style="margin: 5px 0; font-size: 1.1rem;"></p>
                <p id="imcClassification" style="margin: 5px 0; font-weight: bold;"></p>
            </div>
        </section>

        <!-- Histórico de Medidas -->
        <section class="historico-section" style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #4caf50; margin-bottom: 20px; font-size: 1.4rem;">
                📈 Histórico de Medidas
            </h2>
            <div id="historicoContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando histórico...</p>
            </div>
        </section>
    </main>
</div>

<!-- Incluir scripts das classes OOP -->
<script src="/public/scripts/classes/ApiClient.js"></script>
<script src="/public/scripts/classes/MessageManager.js"></script>
<script src="/public/scripts/classes/DadosAntropometricosManager.js"></script>

<script>
// Inicializar aplicação usando OOP
document.addEventListener('DOMContentLoaded', function() {
    const ID_PACIENTE = <?php echo $id_paciente; ?>;
    
    try {
        // Inicializar gerenciador de dados antropométricos
        window.dadosManager = new DadosAntropometricosManager(ID_PACIENTE);
        
        console.log('Dados Antropométricos inicializados com OOP para paciente:', ID_PACIENTE);
        
    } catch (error) {
        console.error('Erro ao inicializar aplicação:', error);
        
        // Fallback para o script original em caso de erro
        const messageDiv = document.getElementById('message') || document.createElement('div');
        messageDiv.style.display = 'block';
        messageDiv.style.backgroundColor = '#f8d7da';
        messageDiv.style.color = '#721c24';
        messageDiv.style.padding = '12px';
        messageDiv.style.borderRadius = '6px';
        messageDiv.style.marginBottom = '15px';
        messageDiv.textContent = 'Erro ao inicializar sistema. Recarregue a página.';
        
        const container = document.querySelector('main, .container, body');
        if (container && !document.getElementById('message')) {
            container.insertBefore(messageDiv, container.firstChild);
        }
    }
});

// Funções globais para compatibilidade com HTML inline
function calcularIMC() {
    if (window.dadosManager) {
        window.dadosManager.calcularIMC();
    }
}

function excluirMedida(id) {
    if (window.dadosManager) {
        window.dadosManager.excluirMedida(id);
    }
}
</script>