<?php
// Verificar se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: /usuario/login');
    exit;
}

// Garantir que temos os dados do paciente
if (empty($_SESSION['paciente']['id_paciente'])) {
    // Se não temos na sessão, tentar buscar no banco
    if (!empty($_SESSION['usuario']['id_usuario'])) {
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
            <?php if (!empty($dadosAntropometricos)): ?>
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

// Formulário de dados
document.getElementById('dadosForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.id_paciente = ID_PACIENTE;

    try {
        const response = await fetch(`${API_BASE}/criar`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
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
    
    fetch(`${API_BASE}/calcular-imc?${params}`)
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

// Função para carregar histórico
function carregarHistorico() {
    const params = new URLSearchParams({ id_paciente: ID_PACIENTE });
    
    fetch(`${API_BASE}/buscar-por-paciente?${params}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.error) {
                document.getElementById('historicoContainer').innerHTML = `<p style="color: red; text-align: center;">Erro: ${result.error}</p>`;
                return;
            }
            
            const dados = result.success ? result.data : result;
            
            if (Array.isArray(dados) && dados.length > 0) {
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
                
                dados.forEach(item => {
                    const altura = Number(item.altura_paciente);
                    const peso = Number(item.peso_paciente);
                    const imc = (altura && peso) ? (peso / (altura * altura)).toFixed(2) : '-';
                    
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
                    
                    const dataFormatada = item.data_medida ? new Date(item.data_medida).toLocaleDateString('pt-BR') : '-';
                    
                    html += `<tr style="border-bottom: 1px solid #eee;">`;
                    html += `<td style="padding: 10px;">${dataFormatada}</td>`;
                    html += `<td style="padding: 10px;">${sexo}</td>`;
                    html += `<td style="padding: 10px;">${item.altura_paciente || '-'}</td>`;
                    html += `<td style="padding: 10px;">${item.peso_paciente || '-'}</td>`;
                    html += `<td style="padding: 10px; font-weight: bold;">${imc}</td>`;
                    html += `<td style="padding: 10px;">${classificacao}</td>`;
                    html += `<td style="padding: 10px; text-align: center;">`;
                    if (item.id_dados_antropometricos) {
                        html += `<button onclick="excluirMedida(${item.id_dados_antropometricos})" style="background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">🗑️ Excluir</button>`;
                    }
                    html += `</td>`;
                    html += `</tr>`;
                });
                html += '</tbody></table></div>';
                document.getElementById('historicoContainer').innerHTML = html;
            } else {
                document.getElementById('historicoContainer').innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma medida encontrada. Adicione sua primeira medida acima!</p>';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('historicoContainer').innerHTML = '<p style="color: red; text-align: center;">Erro ao carregar histórico. Tente recarregar a página.</p>';
        });
}

// Função para excluir medida
function excluirMedida(id) {
    if (!confirm('Tem certeza que deseja excluir esta medida?')) {
        return;
    }
    
    fetch(`${API_BASE}/deletar`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
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
document.addEventListener('DOMContentLoaded', carregarHistorico);
</script>
