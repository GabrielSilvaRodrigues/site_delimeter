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

// Inicializar variáveis para evitar erros
$sexoAtual = '';
$alturaAtual = '';
$pesoAtual = '';
$imcAtual = '';
$classificacaoAtual = '';

// Tentar carregar dados antropométricos da sessão ou banco
try {
    require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
    
    // Verificar se temos dados na sessão
    if (isset($_SESSION['dados_antropometricos']) && !empty($_SESSION['dados_antropometricos'])) {
        $dados = $_SESSION['dados_antropometricos'];
        $sexoAtual = $dados['sexo_paciente'] ?? '';
        $alturaAtual = $dados['altura_paciente'] ?? '';
        $pesoAtual = $dados['peso_paciente'] ?? '';
        $imcAtual = $dados['imc'] ?? '';
        $classificacaoAtual = $dados['classificacao_imc'] ?? '';
    } else {
        // Buscar no banco de dados
        $dadosRepo = new \Htdocs\Src\Models\Repository\DadosAntropometricosRepository();
        $ultimosDados = $dadosRepo->buscarUltimaMedida($id_paciente);
        
        if ($ultimosDados) {
            $sexoAtual = $ultimosDados['sexo_paciente'] ?? '';
            $alturaAtual = $ultimosDados['altura_paciente'] ?? '';
            $pesoAtual = $ultimosDados['peso_paciente'] ?? '';
            
            // Calcular IMC se temos altura e peso
            if ($alturaAtual && $pesoAtual) {
                $imcAtual = $pesoAtual / ($alturaAtual * $alturaAtual);
                
                // Classificar IMC
                if ($imcAtual < 18.5) {
                    $classificacaoAtual = 'Baixo peso';
                } elseif ($imcAtual < 25) {
                    $classificacaoAtual = 'Peso normal';
                } elseif ($imcAtual < 30) {
                    $classificacaoAtual = 'Sobrepeso';
                } else {
                    $classificacaoAtual = 'Obesidade';
                }
            }
            
            // Salvar na sessão para próximas consultas
            $_SESSION['dados_antropometricos'] = [
                'sexo_paciente' => $sexoAtual,
                'altura_paciente' => $alturaAtual,
                'peso_paciente' => $pesoAtual,
                'imc' => $imcAtual,
                'classificacao_imc' => $classificacaoAtual
            ];
        }
    }
} catch (Exception $e) {
    error_log("Erro ao carregar dados antropométricos: " . $e->getMessage());
    // Manter valores vazios em caso de erro
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

        <!-- Mensagem de feedback -->
        <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>

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
                    <button type="button" id="calcularBtn" style="background: #ff9800; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
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

        <div style="text-align: center; margin-top: 30px;">
            <a href="/paciente" style="color: #4caf50; text-decoration: none; font-weight: bold;">
                ← Voltar ao painel do paciente
            </a>
        </div>
    </main>
</div>

<!-- Incluir scripts das classes OOP -->
<script src="/public/scripts/classes/ApiClient.js"></script>
<script src="/public/scripts/classes/MessageManager.js"></script>

<script>
// Classe para gerenciar dados antropométricos
class DadosAntropometricosManager {
    constructor(idPaciente) {
        this.idPaciente = idPaciente;
        this.apiClient = new ApiClient();
        this.messageManager = new MessageManager();
        
        this.initializeElements();
        this.bindEvents();
        this.carregarHistorico();
    }

    initializeElements() {
        this.form = document.getElementById('dadosForm');
        this.calcularBtn = document.getElementById('calcularBtn');
        this.imcResult = document.getElementById('imcResult');
        this.imcValue = document.getElementById('imcValue');
        this.imcClassification = document.getElementById('imcClassification');
        this.historicoContainer = document.getElementById('historicoContainer');
    }

    bindEvents() {
        this.form.addEventListener('submit', (e) => this.salvarDados(e));
        this.calcularBtn.addEventListener('click', () => this.calcularIMC());
    }

    async calcularIMC() {
        const altura = document.getElementById('altura_paciente').value;
        const peso = document.getElementById('peso_paciente').value;

        if (!altura || !peso) {
            this.messageManager.warning('Por favor, preencha altura e peso para calcular o IMC.');
            return;
        }

        try {
            const response = await this.apiClient.get('/api/dados-antropometricos/calcular-imc', {
                altura: altura,
                peso: peso
            });

            if (response.success && response.data) {
                const { imc, classificacao } = response.data;
                
                this.imcValue.textContent = `IMC: ${imc}`;
                this.imcClassification.textContent = `Classificação: ${classificacao}`;
                this.imcClassification.style.color = this.getClassificationColor(classificacao);
                this.imcResult.style.display = 'block';
                
                this.messageManager.success('IMC calculado com sucesso!');
            } else {
                this.messageManager.error(response.error || 'Erro ao calcular IMC');
            }
        } catch (error) {
            console.error('Erro ao calcular IMC:', error);
            this.messageManager.error('Erro ao calcular IMC');
        }
    }

    getClassificationColor(classificacao) {
        const colors = {
            'Baixo peso': '#2196f3',
            'Peso normal': '#4caf50',
            'Sobrepeso': '#ff9800',
            'Obesidade': '#f44336'
        };
        return colors[classificacao] || '#333';
    }

    async salvarDados(event) {
        event.preventDefault();

        const formData = new FormData(this.form);
        const data = {
            id_paciente: this.idPaciente,
            sexo_paciente: formData.get('sexo_paciente'),
            altura_paciente: formData.get('altura_paciente'),
            peso_paciente: formData.get('peso_paciente'),
            data_medida: formData.get('data_medida')
        };

        // Validação básica
        if (!data.altura_paciente || !data.peso_paciente) {
            this.messageManager.warning('Por favor, preencha pelo menos altura e peso.');
            return;
        }

        try {
            const response = await this.apiClient.post('/api/dados-antropometricos/criar', data);

            if (response.success) {
                this.messageManager.success('Dados antropométricos salvos com sucesso!');
                this.carregarHistorico();
                
                // Limpar resultado do IMC
                this.imcResult.style.display = 'none';
                
                // Recarregar página após 2 segundos para atualizar dados no header
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                this.messageManager.error(response.error || 'Erro ao salvar dados');
            }
        } catch (error) {
            console.error('Erro ao salvar dados:', error);
            this.messageManager.error('Erro ao salvar dados antropométricos');
        }
    }

    async carregarHistorico() {
        try {
            const response = await this.apiClient.get('/api/dados-antropometricos/buscar-por-paciente', {
                id_paciente: this.idPaciente
            });

            if (response.success && response.data) {
                this.exibirHistorico(response.data);
            } else {
                this.historicoContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma medida encontrada.</p>';
            }
        } catch (error) {
            console.error('Erro ao carregar histórico:', error);
            this.historicoContainer.innerHTML = '<p style="text-align: center; color: #f44336; padding: 20px;">Erro ao carregar histórico.</p>';
        }
    }

    exibirHistorico(dados) {
        if (!dados || dados.length === 0) {
            this.historicoContainer.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhuma medida encontrada.</p>';
            return;
        }

        let html = `
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Data</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Sexo</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Altura (m)</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Peso (kg)</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">IMC</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Classificação</th>
                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #ddd;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        dados.forEach(medida => {
            const dataFormatada = new Date(medida.data_medida).toLocaleDateString('pt-BR');
            const sexo = medida.sexo_paciente == '1' ? 'Masculino' : 'Feminino';
            const imc = medida.altura_paciente && medida.peso_paciente ? 
                (medida.peso_paciente / (medida.altura_paciente * medida.altura_paciente)).toFixed(2) : '-';
            
            let classificacao = '-';
            if (imc !== '-') {
                const imcNum = parseFloat(imc);
                if (imcNum < 18.5) classificacao = 'Baixo peso';
                else if (imcNum < 25) classificacao = 'Peso normal';
                else if (imcNum < 30) classificacao = 'Sobrepeso';
                else classificacao = 'Obesidade';
            }

            const idMedida = medida.id_medida || medida.id_dados_antropometricos;

            html += `
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">${dataFormatada}</td>
                    <td style="padding: 12px;">${sexo}</td>
                    <td style="padding: 12px;">${medida.altura_paciente || '-'}</td>
                    <td style="padding: 12px;">${medida.peso_paciente || '-'}</td>
                    <td style="padding: 12px; font-weight: bold;">${imc}</td>
                    <td style="padding: 12px; color: ${this.getClassificationColor(classificacao)};">${classificacao}</td>
                    <td style="padding: 12px; text-align: center;">
                        <button onclick="dadosManager.excluirMedida(${idMedida})" 
                                style="background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;">
                            🗑️ Excluir
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
            <p style="text-align: center; color: #666; font-size: 12px; margin-top: 10px;">
                Total de ${dados.length} medida${dados.length !== 1 ? 's' : ''} encontrada${dados.length !== 1 ? 's' : ''}
            </p>
        `;

        this.historicoContainer.innerHTML = html;
    }

    async excluirMedida(idMedida) {
        if (!confirm('Tem certeza que deseja excluir esta medida?')) {
            return;
        }

        try {
            const response = await this.apiClient.delete('/api/dados-antropometricos/deletar', {
                id: idMedida
            });

            if (response.success) {
                this.messageManager.success('Medida excluída com sucesso!');
                this.carregarHistorico();
            } else {
                this.messageManager.error(response.error || 'Erro ao excluir medida');
            }
        } catch (error) {
            console.error('Erro ao excluir medida:', error);
            this.messageManager.error('Erro ao excluir medida');
        }
    }
}

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
</script>