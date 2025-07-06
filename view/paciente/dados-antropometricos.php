<?php
// Verificar se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: /usuario/login');
    exit;
}

// Garantir que temos os dados do paciente
if (!isset($_SESSION['paciente']['id_paciente'])) {
    // Se não temos na sessão, tentar buscar no banco
    if (isset($_SESSION['usuario']['id_usuario'])) {
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
                        <option value="0">Feminino</option>
                        <option value="1">Masculino</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="altura_paciente" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Altura (m):</label>
                    <input type="number" id="altura_paciente" name="altura_paciente" step="0.01" min="0.5" max="2.5" placeholder="Ex: 1.75" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                
                <div class="form-group">
                    <label for="peso_paciente" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Peso (kg):</label>
                    <input type="number" id="peso_paciente" name="peso_paciente" step="0.1" min="20" max="300" placeholder="Ex: 70.5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
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
// Configuração global
const API_BASE = '/api/dados-antropometricos';
const ID_PACIENTE = <?php echo $id_paciente; ?>;

// Formulário de dados
document.getElementById('dadosForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.id_paciente = ID_PACIENTE;
    
    try {
        const response = await fetch(API_BASE + '/criar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Dados antropométricos salvos com sucesso!');
            this.reset();
            document.getElementById('data_medida').value = new Date().toISOString().split('T')[0];
            document.getElementById('imcResult').style.display = 'none';
            carregarHistorico();
        } else {
            alert('Erro: ' + (result.error || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    }
});

// Função para calcular IMC
async function calcularIMC() {
    const altura = document.getElementById('altura_paciente').value;
    const peso = document.getElementById('peso_paciente').value;
    
    if (!altura || !peso) {
        alert('Por favor, preencha altura e peso para calcular o IMC.');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/calcular-imc?altura=${altura}&peso=${peso}`);
        const result = await response.json();
        
        if (result.imc) {
            document.getElementById('imcValue').textContent = `IMC: ${result.imc.toFixed(2)}`;
            document.getElementById('imcClassification').textContent = `Classificação: ${result.classificacao}`;
            document.getElementById('imcResult').style.display = 'block';
        } else {
            alert('Erro ao calcular IMC: ' + (result.error || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    }
}

// Função para carregar histórico
async function carregarHistorico() {
    try {
        const response = await fetch(`${API_BASE}/buscar-por-paciente?id_paciente=${ID_PACIENTE}`);
        const dados = await response.json();
        
        if (Array.isArray(dados) && dados.length > 0) {
            let html = '<div style="overflow-x: auto;"><table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
            html += '<thead><tr style="background: #4caf50; color: white;">';
            html += '<th style="padding: 12px; text-align: left;">Data</th>';
            html += '<th style="padding: 12px; text-align: left;">Sexo</th>';
            html += '<th style="padding: 12px; text-align: left;">Altura (m)</th>';
            html += '<th style="padding: 12px; text-align: left;">Peso (kg)</th>';
            html += '<th style="padding: 12px; text-align: left;">IMC</th>';
            html += '<th style="padding: 12px; text-align: left;">Classificação</th>';
            html += '</tr></thead><tbody>';
            
            dados.forEach(item => {
                const imc = item.altura_paciente && item.peso_paciente ? 
                    (item.peso_paciente / (item.altura_paciente * item.altura_paciente)).toFixed(2) : '-';
                const sexo = item.sexo_paciente === 0 ? 'Feminino' : (item.sexo_paciente === 1 ? 'Masculino' : '-');
                
                let classificacao = '-';
                if (imc !== '-') {
                    const imcNum = parseFloat(imc);
                    if (imcNum < 18.5) classificacao = 'Abaixo do peso';
                    else if (imcNum < 25) classificacao = 'Peso normal';
                    else if (imcNum < 30) classificacao = 'Sobrepeso';
                    else classificacao = 'Obesidade';
                }
                
                html += `<tr style="border-bottom: 1px solid #eee;">`;
                html += `<td style="padding: 10px;">${item.data_medida || '-'}</td>`;
                html += `<td style="padding: 10px;">${sexo}</td>`;
                html += `<td style="padding: 10px;">${item.altura_paciente || '-'}</td>`;
                html += `<td style="padding: 10px;">${item.peso_paciente || '-'}</td>`;
                html += `<td style="padding: 10px; font-weight: bold;">${imc}</td>`;
                html += `<td style="padding: 10px;">${classificacao}</td>`;
                html += `</tr>`;
            });
            
            html += '</tbody></table></div>';
            document.getElementById('historicoContainer').innerHTML = html;
        } else {
            document.getElementById('historicoContainer').innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma medida encontrada. Adicione sua primeira medida acima!</p>';
        }
    } catch (error) {
        console.error('Erro:', error);
        document.getElementById('historicoContainer').innerHTML = '<p style="color: red; text-align: center;">Erro ao carregar histórico. Tente recarregar a página.</p>';
    }
}

// Carregar histórico ao carregar a página
document.addEventListener('DOMContentLoaded', carregarHistorico);
</script>
