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

$id_paciente = (int)$_SESSION['paciente']['id_paciente'];
?>

<div class="consultas-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e8f5e8 100%); min-height: 100vh;">
    <main class="consultas-main-content" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        
        <!-- Header -->
        <div class="consultas-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #4caf50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="font-size:2.2rem; margin-bottom: 8px; color: #fff; text-shadow: 1px 2px 6px rgba(0,0,0,0.2);">
                📅 Consultas
            </h1>
            <p style="font-size:1.1rem; color:#e8f5e8; margin: 0;">
                Agende e acompanhe suas consultas médicas e nutricionais
            </p>
        </div>

        <!-- Formulário para Agendar Nova Consulta -->
        <section class="form-section" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #4caf50; margin-bottom: 20px; font-size: 1.4rem;">
                📋 Agendar Nova Consulta
            </h2>
            <form id="consultaForm" style="display: grid; gap: 15px;">
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="data_consulta" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Data e Hora:</label>
                        <input type="datetime-local" id="data_consulta" name="data_consulta" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div class="form-group">
                        <label for="tipo_profissional" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Tipo de Consulta:</label>
                        <select id="tipo_profissional" name="tipo_profissional" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="">Selecione...</option>
                            <option value="medico">Consulta Médica</option>
                            <option value="nutricionista">Consulta Nutricional</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="observacoes" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Observações (opcional):</label>
                    <textarea id="observacoes" name="observacoes" rows="3" placeholder="Descreva o motivo da consulta ou outras informações importantes..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; resize: vertical;"></textarea>
                </div>
                
                <div class="form-group" style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                    <button type="submit" style="background: #4caf50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        📅 Agendar Consulta
                    </button>
                </div>
            </form>
        </section>

        <!-- Lista de Consultas -->
        <section class="consultas-section" style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #4caf50; margin-bottom: 20px; font-size: 1.4rem;">
                🗓️ Minhas Consultas
            </h2>
            <div id="consultasContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando consultas...</p>
            </div>
        </section>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/paciente" style="color: #4caf50; text-decoration: none; font-weight: bold;">
                ← Voltar ao painel do paciente
            </a>
        </div>
    </main>
</div>

<script src="/public/scripts/classes/ApiClient.js"></script>
<script src="/public/scripts/classes/MessageManager.js"></script>
<script src="/public/scripts/classes/ConsultaManager.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ID_PACIENTE = <?php echo $id_paciente; ?>;
    window.consultaManager = new ConsultaManager(ID_PACIENTE);
    console.log('Consultas inicializadas para paciente:', ID_PACIENTE);
});
</script>
