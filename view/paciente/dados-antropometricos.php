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

$id_paciente = (int)$_SESSION['paciente']['id_paciente'];

// Carregar dados antropométricos da sessão (se existirem)
$dadosAntropometricos = $_SESSION['dados_antropometricos'] ?? [];
$sexoAtual = $dadosAntropometricos['sexo_paciente'] ?? '';
$alturaAtual = $dadosAntropometricos['altura_paciente'] ?? '';
$pesoAtual = $dadosAntropometricos['peso_paciente'] ?? '';
$imcAtual = $dadosAntropometricos['imc'] ?? '';
$classificacaoAtual = $dadosAntropometricos['classificacao_imc'] ?? '';

// Se não temos dados na sessão, tentar carregar do banco
if (empty($dadosAntropometricos)) {
    try {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        $dadosRepo = new \Htdocs\Src\Models\Repository\DadosAntropometricosRepository();
        $dadosService = new \Htdocs\Src\Services\DadosAntropometricosService($dadosRepo);
        
        $ultimaMedida = $dadosService->buscarUltimaMedida($id_paciente);
        if ($ultimaMedida) {
            $sexoAtual = $ultimaMedida['sexo_paciente'] ?? '';
            $alturaAtual = $ultimaMedida['altura_paciente'] ?? '';
            $pesoAtual = $ultimaMedida['peso_paciente'] ?? '';
            
            // Calcular IMC se temos altura e peso
            if ($alturaAtual && $pesoAtual) {
                $imcAtual = $dadosService->calcularIMC($alturaAtual, $pesoAtual);
                $classificacaoAtual = $dadosService->classificarIMC($imcAtual);
                
                // Salvar na sessão
                $_SESSION['dados_antropometricos'] = [
                    'sexo_paciente' => $sexoAtual,
                    'altura_paciente' => $alturaAtual,
                    'peso_paciente' => $pesoAtual,
                    'imc' => $imcAtual,
                    'classificacao_imc' => $classificacaoAtual,
                    'data_medida' => $ultimaMedida['data_medida'] ?? date('Y-m-d')
                ];
            }
        }
    } catch (\Exception $e) {
        error_log("Erro ao carregar dados antropométricos: " . $e->getMessage());
    }
}
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

<script>
const API_BASE = '/api/dados-antropometricos';
const ID_PACIENTE = <?php echo $id_paciente; ?>;

// Função para verificar se a API está funcionando
async function verificarAPI() {
    try {
        // Testar a rota principal que sabemos que funciona
        const response = await fetch(`${API_BASE}/listar`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            console.log('API principal funcionando');
            return true;
        }
        
        throw new Error(`API não responde: ${response.status}`);
        
    } catch (error) {
        console.error('Erro na verificação da API:', error);
        return false;
    }
}

// Função para carregar histórico diretamente
async function carregarHistorico() {
    console.log('Iniciando carregamento do histórico para paciente:', ID_PACIENTE);
    
    document.getElementById('historicoContainer').innerHTML = `
        <p style="text-align: center; color: #666; padding: 20px;">🔄 Carregando histórico...</p>
    `;
    
    // Usar diretamente a API que sabemos que funciona
    const url = `${API_BASE}/buscar-por-paciente?id_paciente=${ID_PACIENTE}`;
    
    console.log('Fazendo requisição para:', url);
    
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            cache: 'no-cache'
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const text = await response.text();
        console.log('Response text (first 200 chars):', text.substring(0, 200));
        
        let result;
        try {
            result = JSON.parse(text);
        } catch (parseError) {
            console.error('Erro ao fazer parse do JSON:', parseError);
            console.error('Text completo:', text);
            
            // Tentar extrair JSON do HTML
            const jsonMatch = text.match(/\{"success".*?\}/);
            if (jsonMatch) {
                try {
                    result = JSON.parse(jsonMatch[0]);
                    console.log('JSON extraído do HTML:', result);
                } catch (extractError) {
                    throw new Error('Não foi possível extrair JSON válido da resposta HTML');
                }
            } else {
                throw new Error('Resposta não contém JSON válido');
            }
        }
        
        console.log('Resultado processado:', result);
        
        // Verificar se a resposta tem estrutura esperada
        if (result.success === true || result.success === false) {
            const dados = result.data || [];
            
            if (Array.isArray(dados) && dados.length > 0) {
                renderizarTabelaHistorico(dados);
            } else {
                mostrarHistoricoVazio();
            }
        } else {
            // Fallback para dados diretos
            const dados = Array.isArray(result) ? result : [];
            
            if (dados.length > 0) {
                renderizarTabelaHistorico(dados);
            } else {
                mostrarHistoricoVazio();
            }
        }
        
    } catch (error) {
        console.error('Erro completo:', error);
        document.getElementById('historicoContainer').innerHTML = `
            <div style="text-align: center; padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px;">
                <h3 style="color: #721c24;">❌ Erro ao carregar histórico</h3>
                <p style="color: #721c24; margin: 10px 0; font-size: 14px;">${error.message}</p>
                <p style="color: #721c24; margin: 10px 0; font-size: 12px;">ID do Paciente: ${ID_PACIENTE}</p>
                <div style="margin-top: 15px;">
                    <button onclick="carregarHistorico()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin: 5px;">
                        🔄 Tentar Novamente
                    </button>
                    <button onclick="debugCompleto()" style="background: #17a2b8; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin: 5px;">
                        🔍 Debug Completo
                    </button>
                </div>
            </div>
        `;
    }
}

// Função para renderizar a tabela do histórico
function renderizarTabelaHistorico(dados) {
    console.log('Renderizando tabela com', dados.length, 'registros');
    
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
    
    dados.forEach((item, index) => {
        console.log(`Processando item ${index}:`, item);
        
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
            html += `<button onclick="excluirMedida(${id})" style="background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">🗑️ Excluir</button>`;
        }
        html += `</td></tr>`;
    });
    
    html += '</tbody></table></div>';
    
    html += `<p style="text-align: center; color: #666; font-size: 12px; margin-top: 10px;">
        Total de ${dados.length} medida${dados.length !== 1 ? 's' : ''} encontrada${dados.length !== 1 ? 's' : ''}
    </p>`;
    
    document.getElementById('historicoContainer').innerHTML = html;
}

// Função para mostrar histórico vazio
function mostrarHistoricoVazio() {
    document.getElementById('historicoContainer').innerHTML = `
        <div style="text-align: center; padding: 20px; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px;">
            <h3 style="color: #1976d2;">📊 Nenhuma medida encontrada</h3>
            <p style="color: #1976d2; margin: 10px 0;">Adicione sua primeira medida usando o formulário acima!</p>
            <button onclick="carregarHistorico()" style="background: #2196f3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                🔄 Atualizar
            </button>
        </div>
    `;
}

// Debug completo
async function debugCompleto() {
    const debugDiv = document.createElement('div');
    debugDiv.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 2px solid #007bff; border-radius: 8px; padding: 20px; max-width: 90%; max-height: 90%; overflow: auto; z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,0.3);';
    
    debugDiv.innerHTML = `
        <h3>🔍 Debug Completo</h3>
        <div id="debugContent">Executando debug...</div>
        <button onclick="this.parentElement.remove()" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-top: 10px;">Fechar</button>
    `;
    
    document.body.appendChild(debugDiv);
    
    const debugContent = document.getElementById('debugContent');
    
    try {
        const response = await fetch(`${API_BASE}/buscar-por-paciente?id_paciente=${ID_PACIENTE}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const text = await response.text();
        
        debugContent.innerHTML = `
            <h4>Informações da Requisição:</h4>
            <p><strong>URL:</strong> ${API_BASE}/buscar-por-paciente?id_paciente=${ID_PACIENTE}</p>
            <p><strong>Status:</strong> ${response.status} ${response.statusText}</p>
            <p><strong>Content-Type:</strong> ${response.headers.get('content-type')}</p>
            <p><strong>ID Paciente:</strong> ${ID_PACIENTE}</p>
            
            <h4>Resposta Completa:</h4>
            <textarea style="width: 100%; height: 200px; font-family: monospace; font-size: 12px;">${text}</textarea>
            
            <h4>Headers da Resposta:</h4>
            <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 12px;">${JSON.stringify(Object.fromEntries(response.headers.entries()), null, 2)}</pre>
        `;
    } catch (error) {
        debugContent.innerHTML = `<p style="color: red;">Erro no debug: ${error.message}</p>`;
    }
}

// Formulário de dados
document.getElementById('dadosForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.id_paciente = ID_PACIENTE;

    try {
        const response = await fetch(`${API_BASE}/criar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        if (result.success) {
            alert('Dados antropométricos salvos com sucesso!');
            this.reset();
            document.getElementById('data_medida').value = new Date().toISOString().split('T')[0];
            document.getElementById('imcResult').style.display = 'none';
            carregarHistorico();
        } else {
            alert('Erro: ' + (result.error || result.message || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    }
});

// Função para calcular IMC
function calcularIMC() {
    const altura = document.getElementById('altura_paciente').value;
    const peso = document.getElementById('peso_paciente').value;
    
    if (!altura || !peso) {
        alert('Por favor, preencha altura e peso para calcular o IMC.');
        return;
    }
    
    const params = new URLSearchParams({ altura, peso });
    
    fetch(`${API_BASE}/calcular-imc?${params}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        if (result.success && result.data) {
            const { imc, classificacao } = result.data;
            document.getElementById('imcValue').textContent = `IMC: ${Number(imc).toFixed(2)}`;
            document.getElementById('imcClassification').textContent = `Classificação: ${classificacao}`;
            document.getElementById('imcResult').style.display = 'block';
        } else {
            alert('Erro ao calcular IMC: ' + (result.error || result.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    });
}

// Função para excluir medida
function excluirMedida(id) {
    if (!confirm('Tem certeza que deseja excluir esta medida?')) {
        return;
    }
    
    fetch(`${API_BASE}/deletar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            alert('Medida excluída com sucesso!');
            carregarHistorico();
        } else {
            alert('Erro ao excluir medida: ' + (result.error || result.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    });
}

// Carregar histórico ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página carregada, iniciando carregamento...');
    console.log('ID do paciente:', ID_PACIENTE);
    
    // Carregar imediatamente, já que sabemos que a API funciona
    carregarHistorico();
});
</script>