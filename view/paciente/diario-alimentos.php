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

// Carregar último registro do diário da sessão (se existir)
$ultimoRegistro = $_SESSION['ultimo_diario'] ?? [];
$ultimaData = $ultimoRegistro['data_diario'] ?? '';
$ultimaDescricao = $ultimoRegistro['descricao_diario'] ?? '';
$quantidadeAlimentos = $ultimoRegistro['quantidade_alimentos'] ?? 0;

// Se não temos dados na sessão, tentar carregar do banco
if (empty($ultimoRegistro)) {
    try {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        $diarioRepo = new \Htdocs\Src\Models\Repository\DiarioDeAlimentosRepository();
        $diarioService = new \Htdocs\Src\Services\DiarioDeAlimentosService($diarioRepo);
        
        $ultimosDiarios = $diarioService->buscarPorPaciente($id_paciente);
        if (!empty($ultimosDiarios)) {
            $ultimo = $ultimosDiarios[0]; // Primeiro registro (mais recente)
            $ultimaData = $ultimo['data_diario'] ?? '';
            $ultimaDescricao = $ultimo['descricao_diario'] ?? '';
            
            // Salvar na sessão
            $_SESSION['ultimo_diario'] = [
                'data_diario' => $ultimaData,
                'descricao_diario' => $ultimaDescricao,
                'quantidade_alimentos' => 0 // Será calculado se necessário
            ];
        }
    } catch (\Exception $e) {
        error_log("Erro ao carregar último diário: " . $e->getMessage());
    }
}
?>

<div class="diario-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #fff3e0 100%); min-height: 100vh;">
    <main class="diario-main-content" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        
        <!-- Header -->
        <div class="diario-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #ff9800 70%, #f57c00 100%); box-shadow: 0 4px 16px rgba(255,152,0,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="font-size:2.2rem; margin-bottom: 8px; color: #fff; text-shadow: 1px 2px 6px rgba(0,0,0,0.2);">
                📔 Diário de Alimentos
            </h1>
            <p style="font-size:1.1rem; color:#fff3e0; margin: 0;">
                Registre seus alimentos diários e acompanhe sua alimentação
            </p>
            <?php if ($ultimaData || $ultimaDescricao): ?>
                <div style="margin-top: 15px; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 8px;">
                    <h3 style="color: #fff; margin: 0 0 10px 0; font-size: 1.1rem;">📈 Último Registro:</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; color: #fff3e0;">
                        <?php if ($ultimaData): ?>
                            <span><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($ultimaData)); ?></span>
                        <?php endif; ?>
                        <?php if ($ultimaDescricao): ?>
                            <span><strong>Descrição:</strong> <?php echo htmlspecialchars(substr($ultimaDescricao, 0, 50)) . (strlen($ultimaDescricao) > 50 ? '...' : ''); ?></span>
                        <?php endif; ?>
                        <?php if ($quantidadeAlimentos > 0): ?>
                            <span><strong>Alimentos:</strong> <?php echo $quantidadeAlimentos; ?> registrados</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Formulário de Novo Registro -->
        <section class="form-section" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #ff9800; margin-bottom: 20px; font-size: 1.4rem;">
                ➕ Novo Registro
            </h2>
            <form id="diarioForm" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                <div class="form-group">
                    <label for="data_diario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Data:</label>
                    <input type="date" id="data_diario" name="data_diario" value="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label for="buscar_alimento" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Buscar Alimento:</label>
                    <input type="text" id="buscar_alimento" placeholder="Digite o nome do alimento..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="descricao_diario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Descrição do Dia:</label>
                    <textarea id="descricao_diario" name="descricao_diario" rows="3" placeholder="Descreva como foi sua alimentação hoje..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; resize: vertical;"></textarea>
                </div>
                
                <!-- Lista de alimentos selecionados -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Alimentos Selecionados:</label>
                    <div id="alimentosList" style="min-height: 60px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
                        <p style="color: #666; margin: 0;">Nenhum alimento selecionado ainda.</p>
                    </div>
                </div>
                
                <div class="form-group" style="grid-column: 1 / -1; display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                    <button type="button" onclick="limparFormulario()" style="background: #9e9e9e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        🗑️ Limpar
                    </button>
                    <button type="submit" style="background: #ff9800; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        💾 Salvar Registro
                    </button>
                </div>
            </form>
        </section>

        <!-- Resultados da Busca de Alimentos -->
        <section id="buscaResultados" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: none;">
            <h3 style="color: #ff9800; margin-bottom: 15px;">Resultados da Busca:</h3>
            <div id="resultadosContainer"></div>
        </section>

        <!-- Histórico do Diário -->
        <section class="historico-section" style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #ff9800; margin-bottom: 20px; font-size: 1.4rem;">
                📅 Histórico do Diário
            </h2>
            <div id="historicoContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando histórico...</p>
            </div>
        </section>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/paciente" style="color: #ff9800; text-decoration: none; font-weight: bold;">
                ← Voltar ao painel do paciente
            </a>
        </div>
    </main>
</div>

<script>
const API_BASE = '/api/diario-alimentos';
const API_ALIMENTOS = '/api/alimentos';
const ID_PACIENTE = <?php echo $id_paciente; ?>;

// Estado da aplicação
let alimentosSelecionados = [];

// Função para verificar se a API está funcionando
async function verificarAPI() {
    try {
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

// Função para buscar alimentos
async function buscarAlimentos(termo) {
    if (termo.length < 2) {
        document.getElementById('buscaResultados').style.display = 'none';
        return;
    }
    
    try {
        const response = await fetch(`${API_ALIMENTOS}/buscar-por-descricao?descricao=${encodeURIComponent(termo)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const text = await response.text();
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (parseError) {
            // Tentar extrair JSON do HTML
            const jsonMatch = text.match(/\{"success".*?\}/);
            if (jsonMatch) {
                result = JSON.parse(jsonMatch[0]);
            } else {
                throw new Error('Resposta não contém JSON válido');
            }
        }
        
        if (result.success && result.data) {
            mostrarResultadosBusca(result.data);
        } else {
            mostrarResultadosBusca([]);
        }
        
    } catch (error) {
        console.error('Erro ao buscar alimentos:', error);
        mostrarMensagem('Erro ao buscar alimentos', 'error');
    }
}

// Função para mostrar resultados da busca
function mostrarResultadosBusca(alimentos) {
    const container = document.getElementById('resultadosContainer');
    const section = document.getElementById('buscaResultados');
    
    if (!alimentos || alimentos.length === 0) {
        container.innerHTML = '<p style="color: #666;">Nenhum alimento encontrado.</p>';
        section.style.display = 'block';
        return;
    }
    
    let html = '<div style="display: grid; gap: 10px; max-height: 300px; overflow-y: auto;">';
    
    alimentos.forEach(alimento => {
        const jaSelecionado = alimentosSelecionados.some(a => a.id === alimento.id_alimento);
        
        html += `
            <div style="border: 1px solid #ddd; border-radius: 5px; padding: 10px; display: flex; justify-content: space-between; align-items: center; background: ${jaSelecionado ? '#e8f5e9' : '#fff'};">
                <div>
                    <strong>${alimento.descricao_alimento || 'Sem descrição'}</strong>
                    <br>
                    <small style="color: #666;">
                        ${alimento.dados_nutricionais || 'Informações nutricionais não disponíveis'}
                    </small>
                </div>
                <button type="button" onclick="adicionarAlimento(${alimento.id_alimento}, '${(alimento.descricao_alimento || '').replace(/'/g, '\\\'')}')" 
                        style="background: ${jaSelecionado ? '#4caf50' : '#ff9800'}; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                    ${jaSelecionado ? '✓ Selecionado' : '+ Adicionar'}
                </button>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
    section.style.display = 'block';
}

// Função para adicionar alimento
function adicionarAlimento(idAlimento, descricao) {
    if (alimentosSelecionados.find(a => a.id === idAlimento)) {
        mostrarMensagem('Alimento já foi selecionado', 'warning');
        return;
    }
    
    alimentosSelecionados.push({ id: idAlimento, descricao: descricao });
    atualizarListaAlimentos();
    
    // Atualizar resultados da busca
    const termo = document.getElementById('buscar_alimento').value.trim();
    if (termo.length >= 2) {
        buscarAlimentos(termo);
    }
    
    mostrarMensagem('Alimento adicionado!', 'success');
}

// Função para remover alimento
function removerAlimento(idAlimento) {
    alimentosSelecionados = alimentosSelecionados.filter(a => a.id !== idAlimento);
    atualizarListaAlimentos();
    
    // Atualizar resultados da busca se estiver visível
    const section = document.getElementById('buscaResultados');
    if (section.style.display !== 'none') {
        const termo = document.getElementById('buscar_alimento').value.trim();
        if (termo.length >= 2) {
            buscarAlimentos(termo);
        }
    }
}

// Função para atualizar lista de alimentos selecionados
function atualizarListaAlimentos() {
    const container = document.getElementById('alimentosList');
    
    if (alimentosSelecionados.length === 0) {
        container.innerHTML = '<p style="color: #666; margin: 0;">Nenhum alimento selecionado ainda.</p>';
        return;
    }
    
    let html = '<div style="display: grid; gap: 8px;">';
    
    alimentosSelecionados.forEach(alimento => {
        html += `
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; border: 1px solid #ddd; border-radius: 3px; background: white;">
                <span>${alimento.descricao}</span>
                <button type="button" onclick="removerAlimento(${alimento.id})" 
                        style="background: #f44336; color: white; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                    ✕
                </button>
            </div>
        `;
    });
    
    html += '</div>';
    html += `
        <div style="margin-top: 10px; padding: 10px; background: #e3f2fd; border-radius: 5px;">
            <p style="margin: 0; color: #1976d2; font-size: 14px;">
                <strong>Total:</strong> ${alimentosSelecionados.length} alimento${alimentosSelecionados.length !== 1 ? 's' : ''} selecionado${alimentosSelecionados.length !== 1 ? 's' : ''}
            </p>
        </div>
    `;
    
    container.innerHTML = html;
}

// Função para carregar histórico
async function carregarHistorico() {
    console.log('Iniciando carregamento do histórico para paciente:', ID_PACIENTE);
    
    document.getElementById('historicoContainer').innerHTML = `
        <p style="text-align: center; color: #666; padding: 20px;">🔄 Carregando histórico...</p>
    `;
    
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
                renderizarHistorico(dados);
            } else {
                mostrarHistoricoVazio();
            }
        } else {
            // Fallback para dados diretos
            const dados = Array.isArray(result) ? result : [];
            
            if (dados.length > 0) {
                renderizarHistorico(dados);
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

// Função para renderizar o histórico
function renderizarHistorico(dados) {
    console.log('Renderizando histórico com', dados.length, 'registros');
    
    let html = '<div style="display: grid; gap: 15px;">';
    
    dados.forEach((registro, index) => {
        console.log(`Processando registro ${index}:`, registro);
        
        const dataFormatada = registro.data_diario ? 
            new Date(registro.data_diario + 'T00:00:00').toLocaleDateString('pt-BR') : '-';
        
        const id = registro.id_diario || registro.id;
        
        html += `
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="margin: 0; color: #ff9800;">📅 ${dataFormatada}</h4>
                    ${id ? `
                        <button onclick="excluirRegistro(${id})" style="background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            🗑️ Excluir
                        </button>
                    ` : ''}
                </div>
                ${registro.descricao_diario ? `<p style="margin: 10px 0; color: #333;"><strong>Descrição:</strong> ${registro.descricao_diario}</p>` : ''}
                <div style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
                    <div id="alimentos-${id}">
                        <p style="color: #666; margin: 0;">Carregando alimentos...</p>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    html += `<p style="text-align: center; color: #666; font-size: 12px; margin-top: 10px;">
        Total de ${dados.length} registro${dados.length !== 1 ? 's' : ''} encontrado${dados.length !== 1 ? 's' : ''}
    </p>`;
    
    document.getElementById('historicoContainer').innerHTML = html;
    
    // Carregar alimentos para cada registro
    dados.forEach(registro => {
        const id = registro.id_diario || registro.id;
        if (id) {
            carregarAlimentosDoRegistro(id);
        }
    });
}

// Função para mostrar histórico vazio
function mostrarHistoricoVazio() {
    document.getElementById('historicoContainer').innerHTML = `
        <div style="text-align: center; padding: 20px; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px;">
            <h3 style="color: #1976d2;">📔 Nenhum registro encontrado</h3>
            <p style="color: #1976d2; margin: 10px 0;">Crie seu primeiro registro de alimentação usando o formulário acima!</p>
            <button onclick="carregarHistorico()" style="background: #2196f3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                🔄 Atualizar
            </button>
        </div>
    `;
}

// Função para carregar alimentos de um registro
async function carregarAlimentosDoRegistro(idDiario) {
    try {
        const response = await fetch(`${API_ALIMENTOS}/buscar-por-diario?id_diario=${idDiario}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const container = document.getElementById(`alimentos-${idDiario}`);
        
        if (response.ok) {
            const text = await response.text();
            let result;
            
            try {
                result = JSON.parse(text);
            } catch (parseError) {
                // Tentar extrair JSON
                const jsonMatch = text.match(/\{"success".*?\}/);
                if (jsonMatch) {
                    result = JSON.parse(jsonMatch[0]);
                } else {
                    result = { success: false, data: [] };
                }
            }
            
            if (result.success && result.data && result.data.length > 0) {
                let html = '<strong style="color: #ff9800;">Alimentos:</strong><div style="margin-top: 5px;">';
                result.data.forEach(alimento => {
                    html += `
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 5px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 3px; background: white;">
                            <span style="font-size: 14px;">${alimento.descricao_alimento}</span>
                            <button onclick="removerAlimentoDoRegistro(${idDiario}, ${alimento.id_alimento})" 
                                    style="background: #f44336; color: white; border: none; padding: 2px 6px; border-radius: 2px; cursor: pointer; font-size: 11px;">
                                Remover
                            </button>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p style="color: #666; margin: 0; font-size: 14px;">Nenhum alimento registrado.</p>';
            }
        } else {
            container.innerHTML = '<p style="color: #f44336; margin: 0; font-size: 14px;">Erro ao carregar alimentos.</p>';
        }
    } catch (error) {
        console.error('Erro ao carregar alimentos do registro:', error);
        const container = document.getElementById(`alimentos-${idDiario}`);
        if (container) {
            container.innerHTML = '<p style="color: #f44336; margin: 0; font-size: 14px;">Erro ao carregar alimentos.</p>';
        }
    }
}

// Debug completo
async function debugCompleto() {
    const debugDiv = document.createElement('div');
    debugDiv.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 2px solid #007bff; border-radius: 8px; padding: 20px; max-width: 90%; max-height: 90%; overflow: auto; z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,0.3);';
    
    debugDiv.innerHTML = `
        <h3>🔍 Debug Completo - Diário de Alimentos</h3>
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

// Função para mostrar mensagens
function mostrarMensagem(mensagem, tipo = 'info') {
    // Criar ou usar elemento de mensagem existente
    let messageEl = document.getElementById('mensagem-sistema');
    if (!messageEl) {
        messageEl = document.createElement('div');
        messageEl.id = 'mensagem-sistema';
        messageEl.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 12px 16px; border-radius: 5px; color: white; font-weight: bold; max-width: 300px;';
        document.body.appendChild(messageEl);
    }
    
    const cores = {
        success: '#4caf50',
        error: '#f44336',
        warning: '#ff9800',
        info: '#2196f3'
    };
    
    messageEl.style.backgroundColor = cores[tipo] || cores.info;
    messageEl.textContent = mensagem;
    messageEl.style.display = 'block';
    
    // Auto-hide após 3 segundos
    setTimeout(() => {
        messageEl.style.display = 'none';
    }, 3000);
}

// Formulário de dados
document.getElementById('diarioForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.id_paciente = ID_PACIENTE;

    if (alimentosSelecionados.length === 0 && !data.descricao_diario.trim()) {
        mostrarMensagem('Por favor, adicione alimentos ou escreva uma descrição.', 'warning');
        return;
    }

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
        
        const text = await response.text();
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (parseError) {
            // Tentar extrair JSON
            const jsonMatch = text.match(/\{"success".*?\}/);
            if (jsonMatch) {
                result = JSON.parse(jsonMatch[0]);
            } else {
                throw new Error('Resposta inválida do servidor');
            }
        }
        
        if (result.success) {
            const idDiario = result.id;
            
            // Associar alimentos ao diário
            for (const alimento of alimentosSelecionados) {
                await fetch(`${API_BASE}/associar-alimento`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_diario: idDiario,
                        id_alimento: alimento.id
                    })
                });
            }
            
            mostrarMensagem('Registro do diário salvo com sucesso!', 'success');
            limparFormulario();
            carregarHistorico();
        } else {
            mostrarMensagem('Erro: ' + (result.error || result.message || 'Erro desconhecido'), 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        mostrarMensagem('Erro de conexão. Tente novamente.', 'error');
    }
});

// Event listener para busca
document.getElementById('buscar_alimento').addEventListener('input', function(e) {
    const termo = e.target.value.trim();
    buscarAlimentos(termo);
});

// Função para excluir registro
function excluirRegistro(id) {
    if (!confirm('Tem certeza que deseja excluir este registro?')) {
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
            mostrarMensagem('Registro excluído com sucesso!', 'success');
            carregarHistorico();
        } else {
            mostrarMensagem('Erro ao excluir registro: ' + (result.error || result.message || 'Erro desconhecido'), 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarMensagem('Erro de conexão. Tente novamente.', 'error');
    });
}

// Função para remover alimento do registro
function removerAlimentoDoRegistro(idDiario, idAlimento) {
    fetch(`${API_BASE}/remover-alimento`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id_diario: idDiario,
            id_alimento: idAlimento
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            mostrarMensagem('Alimento removido com sucesso!', 'success');
            carregarAlimentosDoRegistro(idDiario);
        } else {
            mostrarMensagem('Erro ao remover alimento: ' + (result.error || result.message || 'Erro desconhecido'), 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarMensagem('Erro de conexão. Tente novamente.', 'error');
    });
}

// Função para limpar formulário
function limparFormulario() {
    document.getElementById('diarioForm').reset();
    document.getElementById('data_diario').value = new Date().toISOString().split('T')[0];
    alimentosSelecionados = [];
    atualizarListaAlimentos();
    document.getElementById('buscaResultados').style.display = 'none';
}

// Carregar histórico ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página carregada, iniciando carregamento...');
    console.log('ID do paciente:', ID_PACIENTE);
    
    // Carregar histórico
    carregarHistorico();
});
</script>