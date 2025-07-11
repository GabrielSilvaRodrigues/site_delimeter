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
if (!isset($_SESSION['paciente']['id_paciente'])) {
    // Se não temos na sessão, tentar buscar no banco
    if (isset($_SESSION['usuario']['id_usuario'])) {
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

<div class="diario-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #fff3e0 100%); min-height: 100vh;">
    <main class="diario-main-content" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        
        <!-- Header -->
        <div class="diario-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #ff9800 70%, #f57c00 100%); box-shadow: 0 4px 16px rgba(255,152,0,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="font-size:2.2rem; margin-bottom: 8px; color: #fff; text-shadow: 1px 2px 6px rgba(0,0,0,0.2);">
                📔 Diário de Alimentos
            </h1>
            <p style="font-size:1.1rem; color:#fff3e0; margin: 0;">
                Registre seus alimentos diários e acompanhe sua alimentação
            </p>
        </div>

        <!-- Formulário de Novo Registro -->
        <section class="form-section" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #ff9800; margin-bottom: 20px; font-size: 1.4rem;">
                ➕ Novo Registro
            </h2>
            
            <form id="diarioForm" style="display: grid; gap: 15px;">
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="data_diario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Data:</label>
                        <input type="date" id="data_diario" name="data_diario" value="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    
                    <div class="form-group">
                        <label for="buscar_alimento" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Buscar Alimento:</label>
                        <input type="text" id="buscar_alimento" placeholder="Digite o nome do alimento..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descricao_diario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Descrição do Dia:</label>
                    <textarea id="descricao_diario" name="descricao_diario" rows="3" placeholder="Descreva como foi sua alimentação hoje..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; resize: vertical;"></textarea>
                </div>
                
                <!-- Lista de alimentos selecionados -->
                <div class="alimentos-selecionados" style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: #f9f9f9;">
                    <h3 style="margin: 0 0 10px 0; color: #ff9800;">Alimentos Selecionados:</h3>
                    <div id="alimentosList" style="min-height: 50px;">
                        <p style="color: #666; margin: 0;">Nenhum alimento selecionado ainda.</p>
                    </div>
                </div>
                
                <div class="form-actions" style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
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
        <section id="busca-resultados" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: none;">
            <h3 style="color: #ff9800; margin-bottom: 15px;">Resultados da Busca:</h3>
            <div id="resultados-container"></div>
        </section>

        <!-- Histórico do Diário -->
        <section class="historico-section" style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #ff9800; margin-bottom: 20px; font-size: 1.4rem;">
                📅 Histórico do Diário
            </h2>
            
            <div class="filtros" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center;">
                <div>
                    <label for="filtro_data_inicio" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Data Início:</label>
                    <input type="date" id="filtro_data_inicio" style="padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div>
                    <label for="filtro_data_fim" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Data Fim:</label>
                    <input type="date" id="filtro_data_fim" style="padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-top: 25px;">
                    <button onclick="carregarHistoricoFiltrado()" style="background: #ff9800; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">
                        🔍 Filtrar
                    </button>
                </div>
            </div>
            
            <div id="historicoContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando histórico...</p>
            </div>
        </section>
    </main>
</div>

<script>
// Configuração global
const API_DIARIO = '/api/diario-alimentos';
const API_ALIMENTOS = '/api/alimentos';
const ID_PACIENTE = <?php echo $id_paciente; ?>;

let alimentosSelecionados = [];
let alimentosDisponiveis = []; // Cache de alimentos

// Lista estática de alimentos como fallback
const alimentosFallback = [
    { id_alimento: 1, descricao_alimento: 'Arroz branco cozido', dados_nutricionais: 'Carboidratos: 28g, Proteínas: 2.7g, Gorduras: 0.3g por 100g' },
    { id_alimento: 2, descricao_alimento: 'Feijão preto cozido', dados_nutricionais: 'Carboidratos: 14g, Proteínas: 8.9g, Gorduras: 0.5g por 100g' },
    { id_alimento: 3, descricao_alimento: 'Frango grelhado', dados_nutricionais: 'Carboidratos: 0g, Proteínas: 31g, Gorduras: 3.6g por 100g' },
    { id_alimento: 4, descricao_alimento: 'Batata doce cozida', dados_nutricionais: 'Carboidratos: 20g, Proteínas: 2g, Gorduras: 0.1g por 100g' },
    { id_alimento: 5, descricao_alimento: 'Brócolis refogado', dados_nutricionais: 'Carboidratos: 7g, Proteínas: 3g, Gorduras: 0.4g por 100g' },
    { id_alimento: 6, descricao_alimento: 'Banana', dados_nutricionais: 'Carboidratos: 23g, Proteínas: 1.1g, Gorduras: 0.3g por 100g' },
    { id_alimento: 7, descricao_alimento: 'Maçã', dados_nutricionais: 'Carboidratos: 14g, Proteínas: 0.3g, Gorduras: 0.2g por 100g' },
    { id_alimento: 8, descricao_alimento: 'Leite integral', dados_nutricionais: 'Carboidratos: 4.8g, Proteínas: 3.2g, Gorduras: 3.2g por 100ml' },
    { id_alimento: 9, descricao_alimento: 'Pão integral', dados_nutricionais: 'Carboidratos: 43g, Proteínas: 9g, Gorduras: 4g por 100g' },
    { id_alimento: 10, descricao_alimento: 'Ovos mexidos', dados_nutricionais: 'Carboidratos: 0.6g, Proteínas: 13g, Gorduras: 11g por 100g' }
];

// Debug para verificar se a API está funcionando
console.log('Configurações:', {
    API_DIARIO,
    API_ALIMENTOS,
    ID_PACIENTE
});

// Função para verificar e carregar alimentos
async function carregarAlimentos() {
    try {
        const response = await fetch(`${API_ALIMENTOS}/listar`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const text = await response.text();
            let alimentos;
            
            try {
                alimentos = JSON.parse(text);
            } catch (parseError) {
                console.log('Erro ao parsear JSON de alimentos, usando fallback');
                alimentos = alimentosFallback;
            }
            
            if (Array.isArray(alimentos) && alimentos.length > 0) {
                alimentosDisponiveis = alimentos;
                console.log('Alimentos carregados da API:', alimentos.length);
            } else {
                alimentosDisponiveis = alimentosFallback;
                console.log('Usando alimentos fallback');
            }
        } else {
            console.log('API de alimentos não disponível, usando fallback');
            alimentosDisponiveis = alimentosFallback;
        }
    } catch (error) {
        console.error('Erro ao carregar alimentos:', error);
        alimentosDisponiveis = alimentosFallback;
        console.log('Usando alimentos fallback devido ao erro');
    }
}

// Função para verificar se a API está funcionando
async function verificarAPI() {
    try {
        // Testar a rota principal de diário
        const response = await fetch(`${API_DIARIO}/listar`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            console.log('API de diário funcionando');
            return true;
        }
        
        throw new Error(`API não responde: ${response.status}`);
        
    } catch (error) {
        console.error('Erro na verificação da API:', error);
        return false;
    }
}

// Função para extrair JSON do HTML contaminado
function extrairJSONDaResposta(text) {
    // Procurar por padrões JSON na resposta
    const jsonPattern = /\{"success":(true|false).*?\}(?=<|$)/;
    const match = text.match(jsonPattern);
    
    if (match) {
        try {
            return JSON.parse(match[0]);
        } catch (e) {
            console.error('Erro ao parsear JSON extraído:', e);
            return null;
        }
    }
    
    // Tentar encontrar JSON entre tags HTML
    const startJson = text.indexOf('{"success"');
    const endJson = text.indexOf('}<', startJson);
    
    if (startJson !== -1 && endJson !== -1) {
        const jsonStr = text.substring(startJson, endJson + 1);
        try {
            return JSON.parse(jsonStr);
        } catch (e) {
            console.error('Erro ao parsear JSON extraído (método 2):', e);
            return null;
        }
    }
    
    return null;
}

// Carregar histórico com extração de JSON
async function carregarHistorico() {
    console.log('Carregando histórico para paciente:', ID_PACIENTE);
    
    document.getElementById('historicoContainer').innerHTML = `
        <p style="text-align: center; color: #666; padding: 20px;">🔄 Carregando histórico...</p>
    `;
    
    const url = `${API_DIARIO}/buscar-por-paciente?id_paciente=${ID_PACIENTE}`;
    console.log('Fazendo requisição para:', url);
    
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
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
        
        // Tentar parse direto primeiro
        try {
            result = JSON.parse(text);
        } catch (parseError) {
            console.log('Parse direto falhou, tentando extrair JSON do HTML...');
            
            // Tentar extrair JSON do HTML contaminado
            result = extrairJSONDaResposta(text);
            
            if (!result) {
                console.error('Não foi possível extrair JSON válido da resposta');
                console.error('Text completo:', text);
                throw new Error('Resposta não contém JSON válido');
            }
        }
        
        console.log('Resultado processado:', result);
        
        // A API pode retornar os dados diretamente ou em result.data
        const dados = Array.isArray(result) ? result : (result.data || result);
        
        if (Array.isArray(dados) && dados.length > 0) {
            mostrarHistorico(dados);
        } else if (Array.isArray(dados) && dados.length === 0) {
            document.getElementById('historicoContainer').innerHTML = `
                <div style="text-align: center; padding: 20px; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px;">
                    <h3 style="color: #1976d2;">📔 Nenhum registro encontrado</h3>
                    <p style="color: #1976d2; margin: 10px 0;">Crie seu primeiro registro de alimentação usando o formulário acima!</p>
                    <button onclick="carregarHistorico()" style="background: #2196f3; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                        🔄 Atualizar
                    </button>
                </div>
            `;
        } else {
            console.log('Estrutura inesperada:', result);
            throw new Error('Estrutura de dados inesperada da API');
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
                    <button onclick="debugAPI()" style="background: #17a2b8; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin: 5px;">
                        🔍 Debug Completo
                    </button>
                </div>
            </div>
        `;
    }
}

// Função de debug completo
async function debugAPI() {
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
        const response = await fetch(`${API_DIARIO}/buscar-por-paciente?id_paciente=${ID_PACIENTE}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const text = await response.text();
        
        debugContent.innerHTML = `
            <h4>Informações da Requisição:</h4>
            <p><strong>URL:</strong> ${API_DIARIO}/buscar-por-paciente?id_paciente=${ID_PACIENTE}</p>
            <p><strong>Status:</strong> ${response.status} ${response.statusText}</p>
            <p><strong>Content-Type:</strong> ${response.headers.get('content-type')}</p>
            <p><strong>ID Paciente:</strong> ${ID_PACIENTE}</p>
            
            <h4>Resposta Completa:</h4>
            <textarea style="width: 100%; height: 200px; font-family: monospace; font-size: 12px;">${text}</textarea>
            
            <h4>Headers da Resposta:</h4>
            <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; font-size: 12px;">${JSON.stringify(Object.fromEntries(response.headers.entries()), null, 2)}</pre>
            
            <h4>Teste de Outras Rotas:</h4>
            <div id="otherTests">Testando...</div>
        `;
        
        // Testar outras rotas
        const otherTestsDiv = document.getElementById('otherTests');
        let testResults = '<ul>';
        
        const routes = [
            `${API_DIARIO}/listar`,
            `${API_ALIMENTOS}/listar`,
            '/debug.php'
        ];
        
        for (const route of routes) {
            try {
                const testResponse = await fetch(route, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                testResults += `<li><strong>${route}</strong>: ${testResponse.status} ${testResponse.statusText}`;
                
                if (testResponse.ok) {
                    testResults += ` ✅`;
                } else {
                    testResults += ` ❌`;
                }
                
                testResults += '</li>';
            } catch (error) {
                testResults += `<li><strong>${route}</strong>: Erro - ${error.message} ❌</li>`;
            }
        }
        
        testResults += '</ul>';
        otherTestsDiv.innerHTML = testResults;
        
    } catch (error) {
        debugContent.innerHTML = `<p style="color: red;">Erro no debug: ${error.message}</p>`;
    }
}

function mostrarHistorico(registros) {
    console.log('Renderizando histórico com', registros.length, 'registros');
    
    let html = '<div style="display: grid; gap: 15px;">';
    
    registros.forEach((registro, index) => {
        console.log(`Processando registro ${index}:`, registro);
        
        const dataFormatada = registro.data_diario ? 
            new Date(registro.data_diario + 'T00:00:00').toLocaleDateString('pt-BR') : '-';
        
        // Garantir que temos um ID válido
        const idDiario = registro.id_diario || registro.id;
        console.log(`ID do diário para registro ${index}:`, idDiario);
        
        html += `
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: #f9f9f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="margin: 0; color: #ff9800;">📅 ${dataFormatada}</h3>
                    ${idDiario ? `
                        <button onclick="excluirRegistro(${idDiario})" 
                                style="background: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            🗑️ Excluir
                        </button>
                    ` : `
                        <span style="color: #999; font-size: 12px;">ID não disponível</span>
                    `}
                </div>
                <p style="margin: 10px 0; color: #333; line-height: 1.5;">${registro.descricao_diario || 'Sem descrição'}</p>
                ${idDiario ? `
                    <div id="alimentos-${idDiario}" style="margin-top: 10px;">
                        <p style="color: #666; font-style: italic;">Carregando alimentos...</p>
                    </div>
                ` : `
                    <div style="margin-top: 10px;">
                        <p style="color: #666; font-style: italic;">Dados não disponíveis</p>
                    </div>
                `}
            </div>
        `;
    });
    
    html += '</div>';
    
    html += `<p style="text-align: center; color: #666; font-size: 12px; margin-top: 10px;">
        Total de ${registros.length} registro${registros.length !== 1 ? 's' : ''} encontrado${registros.length !== 1 ? 's' : ''}
    </p>`;
    
    document.getElementById('historicoContainer').innerHTML = html;
    
    // Carregar alimentos para cada registro que tem ID válido
    registros.forEach(registro => {
        const idDiario = registro.id_diario || registro.id;
        if (idDiario) {
            carregarAlimentosDoRegistro(idDiario);
        }
    });
}

// Função para excluir registro - versão melhorada
async function excluirRegistro(id_diario) {
    console.log('Tentando excluir registro com ID:', id_diario);
    
    if (!id_diario) {
        alert('Erro: ID do registro não disponível.');
        return;
    }
    
    if (!confirm('Tem certeza que deseja excluir este registro?')) {
        return;
    }
    
    try {
        // Tentar diferentes formatos de envio
        const response = await fetch(`${API_DIARIO}/deletar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ 
                id: id_diario,
                id_diario: id_diario  // Enviar ambos para garantir
            })
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const text = await response.text();
        console.log('Response text:', text);
        
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (parseError) {
            console.log('Tentando extrair JSON...');
            result = extrairJSONDaResposta(text);
            if (!result) {
                throw new Error('Resposta inválida do servidor');
            }
        }
        
        console.log('Resultado da exclusão:', result);
        
        if (result.success) {
            alert('Registro excluído com sucesso!');
            carregarHistorico();
        } else {
            alert('Erro ao excluir registro: ' + (result.error || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro completo ao excluir:', error);
        
        // Fallback: tentar via GET
        try {
            console.log('Tentando fallback via GET...');
            const fallbackResponse = await fetch(`${API_DIARIO}/deletar?id=${id_diario}&id_diario=${id_diario}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (fallbackResponse.ok) {
                const fallbackText = await fallbackResponse.text();
                let fallbackResult;
                
                try {
                    fallbackResult = JSON.parse(fallbackText);
                } catch (e) {
                    fallbackResult = extrairJSONDaResposta(fallbackText);
                }
                
                if (fallbackResult && fallbackResult.success) {
                    alert('Registro excluído com sucesso!');
                    carregarHistorico();
                    return;
                }
            }
        } catch (fallbackError) {
            console.error('Fallback também falhou:', fallbackError);
        }
        
        alert('Erro de conexão ou problema no servidor. Tente novamente.');
    }
}

// Função para limpar formulário
function limparFormulario() {
    document.getElementById('diarioForm').reset();
    document.getElementById('data_diario').value = new Date().toISOString().split('T')[0];
    document.getElementById('buscar_alimento').value = '';
    alimentosSelecionados = [];
    atualizarListaAlimentosSelecionados();
    document.getElementById('busca-resultados').style.display = 'none';
}

// Função para carregar histórico filtrado
function carregarHistoricoFiltrado() {
    const dataInicio = document.getElementById('filtro_data_inicio').value;
    const dataFim = document.getElementById('filtro_data_fim').value;
    
    if (!dataInicio || !dataFim) {
        alert('Por favor, selecione as datas de início e fim.');
        return;
    }
    
    if (new Date(dataInicio) > new Date(dataFim)) {
        alert('A data de início não pode ser maior que a data de fim.');
        return;
    }
    
    console.log('Carregando histórico filtrado:', { dataInicio, dataFim });
    carregarHistorico();
}

// Remover código duplicado dos event listeners e manter apenas uma função DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página carregada, iniciando carregamento...');
    
    // Carregar alimentos primeiro
    carregarAlimentos().then(() => {
        console.log('Alimentos carregados:', alimentosDisponiveis.length);
    });
    
    // Carregar histórico
    carregarHistorico();
    
    // Verificar registros locais não sincronizados
    mostrarBotaoSincronizar();
    
    // Definir datas padrão para o filtro (última semana)
    const hoje = new Date();
    const umaSemanaAtras = new Date(hoje.getTime() - 7 * 24 * 60 * 60 * 1000);
    
    document.getElementById('filtro_data_inicio').value = umaSemanaAtras.toISOString().split('T')[0];
    document.getElementById('filtro_data_fim').value = hoje.toISOString().split('T')[0];
    
    // Inicializar lista de alimentos selecionados
    atualizarListaAlimentosSelecionados();
    
    // Event listener para busca de alimentos
    document.getElementById('buscar_alimento').addEventListener('input', function(e) {
        const termo = e.target.value.trim();
        
        if (termo.length >= 2) {
            buscarAlimentos(termo);
        } else {
            document.getElementById('busca-resultados').style.display = 'none';
        }
    });
    
    // Event listener único para o formulário
    document.getElementById('diarioForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        data.id_paciente = ID_PACIENTE;
        
        // Adicionar alimentos selecionados aos dados
        data.alimentos_selecionados = alimentosSelecionados;
        
        // Verificar se temos dados suficientes
        if (alimentosSelecionados.length === 0 && !data.descricao_diario.trim()) {
            alert('Por favor, adicione alimentos ou escreva uma descrição do que você comeu.');
            return;
        }
        
        console.log('Enviando dados:', data);
        
        try {
            const response = await fetch(API_DIARIO + '/criar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const text = await response.text();
            console.log('Response text:', text);
            
            let result;
            
            try {
                result = JSON.parse(text);
            } catch (parseError) {
                console.log('Tentando extrair JSON do HTML...');
                result = extrairJSONDaResposta(text);
                if (!result) {
                    throw new Error('Resposta inválida do servidor');
                }
            }
            
            console.log('Resultado final:', result);
            
            if (result && result.success) {
                alert('Registro do diário salvo com sucesso!');
                limparFormulario();
                carregarHistorico();
            } else {
                throw new Error(result?.error || 'Erro desconhecido da API');
            }
        } catch (error) {
            console.error('Erro ao salvar:', error);
            
            // Opção de salvar localmente
            const salvarLocal = confirm(`Erro de conexão: ${error.message}\n\nDeseja salvar o registro localmente para sincronizar depois?`);
            
            if (salvarLocal) {
                salvarLocalmente(data, alimentosSelecionados);
                alert('Registro salvo localmente! Use o botão de sincronização quando a conexão for restabelecida.');
            }
        }
    });
});

// Função melhorada para salvar localmente
function salvarLocalmente(dados, alimentos) {
    try {
        const registroLocal = {
            id: 'local_' + Date.now(),
            data_diario: dados.data_diario,
            descricao_diario: dados.descricao_diario,
            id_paciente: dados.id_paciente,
            alimentos: alimentos.map(a => ({
                id_alimento: a.id_alimento,
                descricao_alimento: a.descricao_alimento,
                dados_nutricionais: a.dados_nutricionais
            })),
            timestamp: new Date().toISOString(),
            sincronizado: false
        };
        
        let registrosLocais = JSON.parse(localStorage.getItem('diario_local') || '[]');
        registrosLocais.push(registroLocal);
        localStorage.setItem('diario_local', JSON.stringify(registrosLocais));
        
        console.log('Registro salvo localmente:', registroLocal);
        
        limparFormulario();
        mostrarBotaoSincronizar();
        
        // Adicionar à exibição local temporariamente
        adicionarRegistroLocalAoHistorico(registroLocal);
        
    } catch (error) {
        console.error('Erro ao salvar localmente:', error);
        alert('Erro ao salvar mesmo localmente. Tente novamente.');
    }
}

// Função para adicionar registro local ao histórico visualmente
function adicionarRegistroLocalAoHistorico(registro) {
    const container = document.getElementById('historicoContainer');
    const historicoAtual = container.innerHTML;
    
    if (historicoAtual.includes('Nenhum registro encontrado')) {
        container.innerHTML = '';
    }
    
    const dataFormatada = new Date(registro.data_diario + 'T00:00:00').toLocaleDateString('pt-BR');
    
    const novoRegistroHTML = `
        <div style="display: grid; gap: 15px; margin-bottom: 15px;">
            <div style="border: 2px solid #ff9800; border-radius: 8px; padding: 20px; background: #fff3e0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="margin: 0; color: #ff9800;">📅 ${dataFormatada} <span style="background: #ff9800; color: white; padding: 2px 8px; border-radius: 10px; font-size: 10px; margin-left: 10px;">LOCAL</span></h3>
                    <button onclick="removerRegistroLocal('${registro.id}')" 
                            style="background: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        🗑️ Remover
                    </button>
                </div>
                <p style="margin: 10px 0; color: #333; line-height: 1.5;">${registro.descricao_diario || 'Sem descrição'}</p>
                ${registro.alimentos.length > 0 ? `
                    <div style="margin-top: 10px;">
                        <strong style="color: #ff9800;">Alimentos:</strong>
                        <ul style="margin: 5px 0 0 20px; color: #333;">
                            ${registro.alimentos.map(a => `<li>${a.descricao_alimento}</li>`).join('')}
                        </ul>
                    </div>
                ` : '<p style="color: #666; font-style: italic;">Nenhum alimento selecionado.</p>'}
                <p style="color: #666; font-size: 11px; margin: 10px 0 0 0; font-style: italic;">
                    *Salvo localmente - será sincronizado automaticamente
                </p>
            </div>
        </div>
    `;
    
    // Adicionar no início do container
    container.innerHTML = novoRegistroHTML + container.innerHTML;
}

// Função para remover registro local
function removerRegistroLocal(id) {
    if (confirm('Deseja remover este registro local?')) {
        let registrosLocais = JSON.parse(localStorage.getItem('diario_local') || '[]');
        registrosLocais = registrosLocais.filter(r => r.id !== id);
        localStorage.setItem('diario_local', JSON.stringify(registrosLocais));
        
        carregarHistorico(); // Recarregar histórico
        mostrarBotaoSincronizar(); // Atualizar botão de sync
    }
}

// Melhorar sincronização
async function sincronizarRegistrosLocais() {
    const registrosLocais = JSON.parse(localStorage.getItem('diario_local') || '[]');
    
    if (registrosLocais.length === 0) {
        alert('Não há registros locais para sincronizar.');
        return;
    }
    
    let sincronizados = 0;
    let erros = 0;
    
    const botaoSync = document.getElementById('sync-button');
    if (botaoSync) {
        botaoSync.innerHTML = '⏳ Sincronizando...';
        botaoSync.style.background = '#6c757d';
    }
    
    for (const registro of registrosLocais) {
        if (!registro.sincronizado) {
            try {
                console.log('Sincronizando registro:', registro);
                
                const response = await fetch(API_DIARIO + '/criar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_paciente: registro.id_paciente,
                        data_diario: registro.data_diario,
                        descricao_diario: registro.descricao_diario,
                        alimentos_selecionados: registro.alimentos
                    })
                });
                
                if (response.ok) {
                    const text = await response.text();
                    let result;
                    
                    try {
                        result = JSON.parse(text);
                    } catch (parseError) {
                        result = extrairJSONDaResposta(text);
                    }
                    
                    if (result && result.success) {
                        registro.sincronizado = true;
                        sincronizados++;
                        console.log('Registro sincronizado com sucesso');
                    } else {
                        erros++;
                        console.error('Erro na resposta da API:', result);
                    }
                } else {
                    erros++;
                    console.error('Erro HTTP:', response.status);
                }
            } catch (error) {
                erros++;
                console.error('Erro ao sincronizar registro:', error);
            }
        }
    }
    
    // Atualizar localStorage
    localStorage.setItem('diario_local', JSON.stringify(registrosLocais));
    
    // Remover registros sincronizados
    const registrosRestantes = registrosLocais.filter(r => !r.sincronizado);
    localStorage.setItem('diario_local', JSON.stringify(registrosRestantes));
    
    // Feedback para o usuário
    if (sincronizados > 0) {
        alert(`✅ ${sincronizados} registro(s) sincronizado(s) com sucesso!${erros > 0 ? `\n⚠️ ${erros} erro(s) encontrado(s).` : ''}`);
        carregarHistorico();
    } else if (erros > 0) {
        alert(`❌ Não foi possível sincronizar os registros (${erros} erro(s)).\nVerifique sua conexão e tente novamente.`);
    }
    
    // Atualizar ou remover botão de sincronização
    if (registrosRestantes.length === 0) {
        if (botaoSync) {
            botaoSync.remove();
        }
    } else {
        mostrarBotaoSincronizar();
    }
}

// Melhorar botão de sincronização
function mostrarBotaoSincronizar() {
    const registrosLocais = JSON.parse(localStorage.getItem('diario_local') || '[]');
    const registrosNaoSincronizados = registrosLocais.filter(r => !r.sincronizado);
    
    // Remover botão existente
    const botaoExistente = document.getElementById('sync-button');
    if (botaoExistente) {
        botaoExistente.remove();
    }
    
    if (registrosNaoSincronizados.length > 0) {
        const botaoSync = document.createElement('div');
        botaoSync.id = 'sync-button';
        botaoSync.style.cssText = `
            position: fixed; 
            top: 20px; 
            right: 20px; 
            background: #ff9800; 
            color: white; 
            padding: 12px 16px; 
            border-radius: 8px; 
            cursor: pointer; 
            z-index: 1000; 
            box-shadow: 0 4px 12px rgba(255,152,0,0.4);
            font-size: 14px;
            font-weight: bold;
            border: 2px solid #f57c00;
            transition: all 0.3s ease;
        `;
        
        botaoSync.innerHTML = `
            📡 ${registrosNaoSincronizados.length} registro(s) local(is)
            <br><small style="font-size: 11px; opacity: 0.9;">Clique para sincronizar</small>
        `;
        
        botaoSync.onclick = sincronizarRegistrosLocais;
        
        // Efeitos hover
        botaoSync.onmouseenter = function() {
            this.style.transform = 'scale(1.05)';
            this.style.background = '#f57c00';
        };
        
        botaoSync.onmouseleave = function() {
            this.style.transform = 'scale(1)';
            this.style.background = '#ff9800';
        };
        
        document.body.appendChild(botaoSync);
    }
}

// Função para carregar alimentos de um registro específico
async function carregarAlimentosDoRegistro(id_diario) {
    try {
        // Tentar primeiro a API
        const response = await fetch(`${API_ALIMENTOS}/buscar-por-diario?id_diario=${id_diario}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const container = document.getElementById(`alimentos-${id_diario}`);
        
        if (response.ok) {
            const text = await response.text();
            let alimentos;
            
            try {
                alimentos = JSON.parse(text);
            } catch (parseError) {
                // Se não conseguir parsear, tentar extrair JSON
                alimentos = extrairJSONDaResposta(text);
                if (!alimentos) {
                    alimentos = [];
                }
            }
            
            if (Array.isArray(alimentos) && alimentos.length > 0) {
                let html = '<strong style="color: #ff9800;">Alimentos:</strong><ul style="margin: 5px 0 0 20px; color: #333;">';
                alimentos.forEach(alimento => {
                    html += `<li>${alimento.descricao_alimento}</li>`;
                });
                html += '</ul>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p style="color: #666; font-style: italic;">Nenhum alimento registrado.</p>';
            }
        } else {
            // Fallback: mostrar alimentos genéricos baseados no horário/tipo
            container.innerHTML = `
                <p style="color: #666; font-style: italic;">
                    <span style="color: #ff9800;">Alimentos:</span> Dados não disponíveis via API. 
                    <button onclick="editarAlimentosRegistro(${id_diario})" 
                            style="background: #ff9800; color: white; padding: 2px 8px; border: none; border-radius: 3px; cursor: pointer; font-size: 11px; margin-left: 5px;">
                        ✏️ Editar
                    </button>
                </p>
            `;
        }
    } catch (error) {
        console.error('Erro ao carregar alimentos:', error);
        const container = document.getElementById(`alimentos-${id_diario}`);
        if (container) {
            container.innerHTML = `
                <p style="color: #666; font-style: italic;">
                    <span style="color: #ff9800;">Alimentos:</span> Erro de conexão. 
                    <button onclick="tentarNovamenteAlimentos(${id_diario})" 
                            style="background: #f44336; color: white; padding: 2px 8px; border: none; border-radius: 3px; cursor: pointer; font-size: 11px; margin-left: 5px;">
                        🔄 Tentar novamente
                    </button>
                </p>
            `;
        }
    }
}

function editarAlimentosRegistro(id_diario) {
    const container = document.getElementById(`alimentos-${id_diario}`);
    container.innerHTML = `
        <div style="background: #fff3cd; padding: 10px; border-radius: 5px; border: 1px solid #ffeaa7;">
            <p style="margin: 0 0 10px 0; color: #856404; font-size: 13px;">
                <strong>Edição de alimentos para este registro:</strong>
            </p>
            <input type="text" id="edit-alimentos-${id_diario}" placeholder="Digite os alimentos separados por vírgula..." 
                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 10px;">
            <div style="display: flex; gap: 5px;">
                <button onclick="salvarAlimentosEditados(${id_diario})" 
                        style="background: #28a745; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                    💾 Salvar
                </button>
                <button onclick="carregarAlimentosDoRegistro(${id_diario})" 
                        style="background: #6c757d; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                    ❌ Cancelar
                </button>
            </div>
        </div>
    `;
}

function salvarAlimentosEditados(id_diario) {
    const input = document.getElementById(`edit-alimentos-${id_diario}`);
    const alimentosTexto = input.value.trim();
    
    if (alimentosTexto) {
        const alimentos = alimentosTexto.split(',').map(a => a.trim()).filter(a => a);
        
        const container = document.getElementById(`alimentos-${id_diario}`);
        let html = '<strong style="color: #ff9800;">Alimentos:</strong><ul style="margin: 5px 0 0 20px; color: #333;">';
        alimentos.forEach(alimento => {
            html += `<li>${alimento}</li>`;
        });
        html += '</ul>';
        html += `<p style="color: #666; font-size: 11px; margin: 5px 0 0 0; font-style: italic;">
                    *Editado manualmente - 
                    <button onclick="editarAlimentosRegistro(${id_diario})" 
                            style="background: none; border: none; color: #ff9800; cursor: pointer; font-size: 11px; text-decoration: underline;">
                        editar novamente
                    </button>
                 </p>`;
        container.innerHTML = html;
        
        // Aqui você poderia fazer uma chamada para salvar no backend se necessário
        console.log(`Alimentos editados para registro ${id_diario}:`, alimentos);
    } else {
        alert('Por favor, digite pelo menos um alimento.');
    }
}

function tentarNovamenteAlimentos(id_diario) {
    carregarAlimentosDoRegistro(id_diario);
}

// Remover os event listeners duplicados e manter apenas um
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página carregada, iniciando carregamento...');
    
    // Carregar alimentos primeiro
    carregarAlimentos().then(() => {
        console.log('Alimentos carregados:', alimentosDisponiveis.length);
    });
    
    // Carregar histórico
    carregarHistorico();
    
    // Verificar registros locais não sincronizados
    mostrarBotaoSincronizar();
    
    // Definir datas padrão para o filtro (última semana)
    const hoje = new Date();
    const umaSemanaAtras = new Date(hoje.getTime() - 7 * 24 * 60 * 60 * 1000);
    
    document.getElementById('filtro_data_inicio').value = umaSemanaAtras.toISOString().split('T')[0];
    document.getElementById('filtro_data_fim').value = hoje.toISOString().split('T')[0];
    
    // Inicializar lista de alimentos selecionados
    atualizarListaAlimentosSelecionados();
    
    // Event listener para busca de alimentos
    document.getElementById('buscar_alimento').addEventListener('input', function(e) {
        const termo = e.target.value.trim();
        
        if (termo.length >= 2) {
            buscarAlimentos(termo);
        } else {
            document.getElementById('busca-resultados').style.display = 'none';
        }
    });
    
    // Event listener único para o formulário
    document.getElementById('diarioForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        data.id_paciente = ID_PACIENTE;
        
        // Adicionar alimentos selecionados aos dados
        data.alimentos_selecionados = alimentosSelecionados;
        
        // Verificar se temos dados suficientes
        if (alimentosSelecionados.length === 0 && !data.descricao_diario.trim()) {
            alert('Por favor, adicione alimentos ou escreva uma descrição do que você comeu.');
            return;
        }
        
        console.log('Enviando dados:', data);
        
        try {
            const response = await fetch(API_DIARIO + '/criar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const text = await response.text();
            console.log('Response text:', text);
            
            let result;
            
            try {
                result = JSON.parse(text);
            } catch (parseError) {
                console.log('Tentando extrair JSON do HTML...');
                result = extrairJSONDaResposta(text);
                if (!result) {
                    throw new Error('Resposta inválida do servidor');
                }
            }
            
            console.log('Resultado final:', result);
            
            if (result && result.success) {
                alert('Registro do diário salvo com sucesso!');
                limparFormulario();
                carregarHistorico();
            } else {
                throw new Error(result?.error || 'Erro desconhecido da API');
            }
        } catch (error) {
            console.error('Erro ao salvar:', error);
            
            // Opção de salvar localmente
            const salvarLocal = confirm(`Erro de conexão: ${error.message}\n\nDeseja salvar o registro localmente para sincronizar depois?`);
            
            if (salvarLocal) {
                salvarLocalmente(data, alimentosSelecionados);
                alert('Registro salvo localmente! Use o botão de sincronização quando a conexão for restabelecida.');
            }
        }
    });
});

// Função para buscar alimentos na lista
function buscarAlimentos(termo) {
    console.log('Buscando alimentos com termo:', termo);
    
    if (alimentosDisponiveis.length === 0) {
        console.log('Carregando alimentos primeiro...');
        carregarAlimentos().then(() => buscarAlimentos(termo));
        return;
    }
    
    const termoBusca = termo.toLowerCase();
    const resultados = alimentosDisponiveis.filter(alimento => 
        alimento.descricao_alimento.toLowerCase().includes(termoBusca)
    );
    
    console.log('Resultados encontrados:', resultados.length);
    
    mostrarResultadosBusca(resultados, termo);
}

// Função para mostrar resultados da busca
function mostrarResultadosBusca(resultados, termo) {
    const container = document.getElementById('resultados-container');
    const section = document.getElementById('busca-resultados');
    
    if (resultados.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
                <p style="color: #856404; margin: 0;">Nenhum alimento encontrado para "${termo}"</p>
                <button onclick="adicionarAlimentoPersonalizado('${termo}')" 
                        style="background: #ff9800; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;">
                    ➕ Adicionar "${termo}" como alimento personalizado
                </button>
            </div>
        `;
    } else {
        let html = '<div style="display: grid; gap: 10px; max-height: 300px; overflow-y: auto;">';
        
        resultados.forEach(alimento => {
            const jaSelecionado = alimentosSelecionados.some(a => a.id_alimento === alimento.id_alimento);
            
            html += `
                <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: ${jaSelecionado ? '#e8f5e9' : '#fff'}; cursor: pointer;" 
                     onclick="selecionarAlimento(${alimento.id_alimento}, '${alimento.descricao_alimento.replace(/'/g, "\\'")}', '${(alimento.dados_nutricionais || '').replace(/'/g, "\\'")}')">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; color: #ff9800;">${alimento.descricao_alimento}</h4>
                        <span style="background: ${jaSelecionado ? '#4caf50' : '#ff9800'}; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px;">
                            ${jaSelecionado ? '✓ Selecionado' : '+ Adicionar'}
                        </span>
                    </div>
                    ${alimento.dados_nutricionais ? `<p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">${alimento.dados_nutricionais}</p>` : ''}
                </div>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
    }
    
    section.style.display = 'block';
}

// Função para selecionar alimento
function selecionarAlimento(id, descricao, dadosNutricionais = '') {
    console.log('Selecionando alimento:', { id, descricao, dadosNutricionais });
    
    // Verificar se já está selecionado
    const jaExiste = alimentosSelecionados.find(a => a.id_alimento === id);
    
    if (jaExiste) {
        // Remover se já estiver selecionado
        alimentosSelecionados = alimentosSelecionados.filter(a => a.id_alimento !== id);
        console.log('Alimento removido da seleção');
    } else {
        // Adicionar à seleção
        alimentosSelecionados.push({
            id_alimento: id,
            descricao_alimento: descricao,
            dados_nutricionais: dadosNutricionais
        });
        console.log('Alimento adicionado à seleção');
    }
    
    atualizarListaAlimentosSelecionados();
    
    // Atualizar resultados da busca para mostrar status atualizado
    const termo = document.getElementById('buscar_alimento').value.trim();
    if (termo.length >= 2) {
        buscarAlimentos(termo);
    }
}

// Função para adicionar alimento personalizado
function adicionarAlimentoPersonalizado(nome) {
    const descricao = prompt(`Digite a descrição completa do alimento "${nome}":`, nome);
    
    if (descricao && descricao.trim()) {
        const dadosNutricionais = prompt('Digite informações nutricionais (opcional):', 'Informações não disponíveis');
        
        const alimentoPersonalizado = {
            id_alimento: 'custom_' + Date.now(),
            descricao_alimento: descricao.trim(),
            dados_nutricionais: dadosNutricionais || 'Alimento personalizado'
        };
        
        // Adicionar ao cache de alimentos disponíveis
        alimentosDisponiveis.push(alimentoPersonalizado);
        
        // Selecionar automaticamente
        alimentosSelecionados.push(alimentoPersonalizado);
        
        atualizarListaAlimentosSelecionados();
        
        // Limpar busca
        document.getElementById('buscar_alimento').value = '';
        document.getElementById('busca-resultados').style.display = 'none';
        
        alert(`Alimento "${descricao}" adicionado e selecionado!`);
    }
}

// Função para atualizar lista de alimentos selecionados
function atualizarListaAlimentosSelecionados() {
    const container = document.getElementById('alimentosList');
    
    if (alimentosSelecionados.length === 0) {
        container.innerHTML = '<p style="color: #666; margin: 0;">Nenhum alimento selecionado ainda.</p>';
        return;
    }
    
    let html = '<div style="display: grid; gap: 8px;">';
    
    alimentosSelecionados.forEach((alimento, index) => {
        html += `
            <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <div>
                    <strong style="color: #ff9800;">${alimento.descricao_alimento}</strong>
                    ${alimento.dados_nutricionais ? `<br><small style="color: #666;">${alimento.dados_nutricionais}</small>` : ''}
                </div>
                <button onclick="removerAlimentoSelecionado(${index})" 
                        style="background: #f44336; color: white; border: none; padding: 5px 8px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                    ✕
                </button>
            </div>
        `;
    });
    
    html += '</div>';
    
    html += `
        <div style="margin-top: 10px; padding: 10px; background: #e3f2fd; border-radius: 5px; border: 1px solid #bbdefb;">
            <p style="margin: 0; color: #1976d2; font-size: 14px;">
                <strong>Total:</strong> ${alimentosSelecionados.length} alimento${alimentosSelecionados.length !== 1 ? 's' : ''} selecionado${alimentosSelecionados.length !== 1 ? 's' : ''}
            </p>
        </div>
    `;
    
    container.innerHTML = html;
}

// Função para remover alimento selecionado
function removerAlimentoSelecionado(index) {
    if (index >= 0 && index < alimentosSelecionados.length) {
        const alimento = alimentosSelecionados[index];
        alimentosSelecionados.splice(index, 1);
        
        console.log('Alimento removido:', alimento.descricao_alimento);
        atualizarListaAlimentosSelecionados();
        
        // Atualizar resultados da busca se estiver visível
        const section = document.getElementById('busca-resultados');
        if (section.style.display !== 'none') {
            const termo = document.getElementById('buscar_alimento').value.trim();
            if (termo.length >= 2) {
                buscarAlimentos(termo);
            }
        }
    }
}

// Função para carregar histórico e alimentos ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página carregada, iniciando carregamento...');
    
    // Carregar alimentos primeiro
    carregarAlimentos().then(() => {
        console.log('Alimentos carregados:', alimentosDisponiveis.length);
    });
    
    // Carregar histórico
    carregarHistorico();
    
    // Verificar registros locais não sincronizados
    mostrarBotaoSincronizar();
    
    // Definir datas padrão para o filtro (última semana)
    const hoje = new Date();
    const umaSemanaAtras = new Date(hoje.getTime() - 7 * 24 * 60 * 60 * 1000);
    
    document.getElementById('filtro_data_inicio').value = umaSemanaAtras.toISOString().split('T')[0];
    document.getElementById('filtro_data_fim').value = hoje.toISOString().split('T')[0];
    
    // Inicializar lista de alimentos selecionados
    atualizarListaAlimentosSelecionados();
});
</script>
