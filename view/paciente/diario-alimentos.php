<?php
// Verificar se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: /usuario/login');
    exit;
}

$id_paciente = $_SESSION['paciente']['id_paciente'] ?? null;
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
const ID_PACIENTE = <?php echo $id_paciente ? $id_paciente : 'null'; ?>;

let alimentosSelecionados = [];

// Formulário de diário
document.getElementById('diarioForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!ID_PACIENTE) {
        alert('Erro: ID do paciente não encontrado.');
        return;
    }
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.id_paciente = ID_PACIENTE;
    
    try {
        // Primeiro criar o diário
        const response = await fetch(API_DIARIO + '/criar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            const id_diario = result.id;
            
            // Depois associar os alimentos
            for (const alimento of alimentosSelecionados) {
                await fetch(API_DIARIO + '/associar-alimento', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_diario: id_diario,
                        id_alimento: alimento.id_alimento
                    })
                });
            }
            
            alert('Registro do diário salvo com sucesso!');
            limparFormulario();
            carregarHistorico();
        } else {
            alert('Erro: ' + (result.error || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    }
});

// Busca de alimentos
document.getElementById('buscar_alimento').addEventListener('input', function() {
    const termo = this.value.trim();
    if (termo.length >= 2) {
        buscarAlimentos(termo);
    } else {
        document.getElementById('busca-resultados').style.display = 'none';
    }
});

async function buscarAlimentos(termo) {
    try {
        const response = await fetch(`${API_ALIMENTOS}/buscar-por-descricao?descricao=${encodeURIComponent(termo)}`);
        const alimentos = await response.json();
        
        if (Array.isArray(alimentos) && alimentos.length > 0) {
            mostrarResultados(alimentos);
        } else {
            document.getElementById('resultados-container').innerHTML = '<p style="color: #666;">Nenhum alimento encontrado.</p>';
            document.getElementById('busca-resultados').style.display = 'block';
        }
    } catch (error) {
        console.error('Erro ao buscar alimentos:', error);
    }
}

function mostrarResultados(alimentos) {
    let html = '<div style="display: grid; gap: 10px;">';
    
    alimentos.forEach(alimento => {
        html += `
            <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: #f9f9f9; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #333;">${alimento.descricao_alimento}</h4>
                    <p style="margin: 0; color: #666; font-size: 0.9rem;">${alimento.dados_nutricionais || 'Dados nutricionais não informados'}</p>
                </div>
                <button onclick="adicionarAlimento(${JSON.stringify(alimento).replace(/"/g, '&quot;')})" 
                        style="background: #4caf50; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">
                    ➕ Adicionar
                </button>
            </div>
        `;
    });
    
    html += '</div>';
    
    document.getElementById('resultados-container').innerHTML = html;
    document.getElementById('busca-resultados').style.display = 'block';
}

function adicionarAlimento(alimento) {
    // Verificar se já foi adicionado
    if (alimentosSelecionados.find(a => a.id_alimento === alimento.id_alimento)) {
        alert('Este alimento já foi adicionado!');
        return;
    }
    
    alimentosSelecionados.push(alimento);
    atualizarListaAlimentos();
    document.getElementById('buscar_alimento').value = '';
    document.getElementById('busca-resultados').style.display = 'none';
}

function atualizarListaAlimentos() {
    const container = document.getElementById('alimentosList');
    
    if (alimentosSelecionados.length === 0) {
        container.innerHTML = '<p style="color: #666; margin: 0;">Nenhum alimento selecionado ainda.</p>';
        return;
    }
    
    let html = '<div style="display: grid; gap: 8px;">';
    
    alimentosSelecionados.forEach((alimento, index) => {
        html += `
            <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                <span style="color: #333; font-weight: 500;">${alimento.descricao_alimento}</span>
                <button onclick="removerAlimento(${index})" 
                        style="background: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                    ✕ Remover
                </button>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

function removerAlimento(index) {
    alimentosSelecionados.splice(index, 1);
    atualizarListaAlimentos();
}

function limparFormulario() {
    document.getElementById('diarioForm').reset();
    document.getElementById('data_diario').value = new Date().toISOString().split('T')[0];
    alimentosSelecionados = [];
    atualizarListaAlimentos();
    document.getElementById('busca-resultados').style.display = 'none';
}

// Carregar histórico
async function carregarHistorico() {
    if (!ID_PACIENTE) {
        document.getElementById('historicoContainer').innerHTML = '<p style="color: red; text-align: center;">Erro: ID do paciente não encontrado.</p>';
        return;
    }
    
    try {
        const response = await fetch(`${API_DIARIO}/buscar-por-paciente?id_paciente=${ID_PACIENTE}`);
        const registros = await response.json();
        
        if (Array.isArray(registros) && registros.length > 0) {
            mostrarHistorico(registros);
        } else {
            document.getElementById('historicoContainer').innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhum registro encontrado. Crie seu primeiro registro acima!</p>';
        }
    } catch (error) {
        console.error('Erro:', error);
        document.getElementById('historicoContainer').innerHTML = '<p style="color: red; text-align: center;">Erro ao carregar histórico. Tente recarregar a página.</p>';
    }
}

function mostrarHistorico(registros) {
    let html = '<div style="display: grid; gap: 15px;">';
    
    registros.forEach(registro => {
        html += `
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: #f9f9f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="margin: 0; color: #ff9800;">📅 ${registro.data_diario}</h3>
                    <button onclick="excluirRegistro(${registro.id_diario})" 
                            style="background: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        🗑️ Excluir
                    </button>
                </div>
                <p style="margin: 10px 0; color: #333; line-height: 1.5;">${registro.descricao_diario || 'Sem descrição'}</p>
                <div id="alimentos-${registro.id_diario}" style="margin-top: 10px;">
                    <p style="color: #666; font-style: italic;">Carregando alimentos...</p>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    document.getElementById('historicoContainer').innerHTML = html;
    
    // Carregar alimentos para cada registro
    registros.forEach(registro => {
        carregarAlimentosDoRegistro(registro.id_diario);
    });
}

async function carregarAlimentosDoRegistro(id_diario) {
    try {
        const response = await fetch(`${API_ALIMENTOS}/buscar-por-diario?id_diario=${id_diario}`);
        const alimentos = await response.json();
        
        const container = document.getElementById(`alimentos-${id_diario}`);
        
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
    } catch (error) {
        console.error('Erro ao carregar alimentos:', error);
        document.getElementById(`alimentos-${id_diario}`).innerHTML = '<p style="color: red; font-style: italic;">Erro ao carregar alimentos.</p>';
    }
}

async function excluirRegistro(id_diario) {
    if (!confirm('Tem certeza que deseja excluir este registro?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_DIARIO}/deletar?id=${id_diario}`, {
            method: 'DELETE'
        });
        const result = await response.json();
        
        if (result.success) {
            alert('Registro excluído com sucesso!');
            carregarHistorico();
        } else {
            alert('Erro ao excluir registro: ' + (result.error || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    }
}

function carregarHistoricoFiltrado() {
    const dataInicio = document.getElementById('filtro_data_inicio').value;
    const dataFim = document.getElementById('filtro_data_fim').value;
    
    if (dataInicio && dataFim) {
        // Implementar filtro por período
        alert('Funcionalidade de filtro por período será implementada em breve!');
    } else {
        carregarHistorico();
    }
}

// Carregar histórico ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    carregarHistorico();
    
    // Definir datas padrão para o filtro (última semana)
    const hoje = new Date();
    const umaSemanaAtras = new Date(hoje.getTime() - 7 * 24 * 60 * 60 * 1000);
    
    document.getElementById('filtro_data_inicio').value = umaSemanaAtras.toISOString().split('T')[0];
    document.getElementById('filtro_data_fim').value = hoje.toISOString().split('T')[0];
});
</script>
