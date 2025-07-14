<?php
// Verificar se os dados do paciente foram carregados
if (!isset($paciente) || !$paciente) {
    http_response_code(404);
    echo "Paciente não encontrado";
    exit;
}
?>

<div class="medico-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%); min-height: 100vh;">
    <main class="medico-main-content" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        
        <!-- Header -->
        <div class="medico-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #1976d2 70%, #1565c0 100%); box-shadow: 0 4px 16px rgba(25,118,210,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="font-size:2.2rem; margin-bottom: 8px; color: #fff; text-shadow: 1px 2px 6px rgba(0,0,0,0.2);">
                👤 Detalhes do Paciente: <?php echo htmlspecialchars($paciente['nome_usuario']); ?>
            </h1>
            <p style="font-size:1.1rem; color:#e0f7fa; margin: 0;">
                Informações completas e histórico médico
            </p>
        </div>

        <!-- Mensagem de feedback -->
        <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>

        <!-- Informações Básicas -->
        <section class="info-basica" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #1976d2; margin-bottom: 20px;">📋 Informações Básicas</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <h3 style="color: #1976d2; margin-bottom: 10px;">Dados Pessoais</h3>
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($paciente['nome_usuario']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($paciente['email_usuario']); ?></p>
                    <p><strong>CPF:</strong> <?php echo htmlspecialchars($paciente['cpf'] ?: 'Não informado'); ?></p>
                    <p><strong>NIS:</strong> <?php echo htmlspecialchars($paciente['nis'] ?: 'Não informado'); ?></p>
                    <p><strong>Status:</strong> <?php echo $paciente['status_usuario'] ? 'Ativo' : 'Inativo'; ?></p>
                </div>
                
                <div>
                    <h3 style="color: #1976d2; margin-bottom: 10px;">Contato</h3>
                    <p><strong>Endereço:</strong> <?php echo htmlspecialchars($paciente['endereco'] ?: 'Não informado'); ?></p>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($paciente['telefone'] ?: 'Não informado'); ?></p>
                </div>
            </div>
        </section>

        <!-- Medidas Antropométricas -->
        <section class="medidas" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #1976d2; margin-bottom: 20px;">📏 Medidas Antropométricas</h2>
            
            <?php if (!empty($paciente['medidas'])): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5;">
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Data</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Peso (kg)</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Altura (m)</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">IMC</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Sexo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paciente['medidas'] as $medida): ?>
                                <?php 
                                $imc = '';
                                if ($medida['peso_paciente'] && $medida['altura_paciente']) {
                                    $imcValue = $medida['peso_paciente'] / ($medida['altura_paciente'] * $medida['altura_paciente']);
                                    $imc = number_format($imcValue, 1);
                                }
                                ?>
                                <tr>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <?php echo date('d/m/Y', strtotime($medida['data_medida'])); ?>
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <?php echo $medida['peso_paciente'] ?: 'N/A'; ?>
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <?php echo $medida['altura_paciente'] ?: 'N/A'; ?>
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <?php echo $imc ?: 'N/A'; ?>
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <?php echo $medida['sexo_paciente'] ? 'Masculino' : 'Feminino'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: #666;">Nenhuma medida antropométrica registrada.</p>
            <?php endif; ?>
        </section>

        <!-- Diários Alimentares Recentes -->
        <section class="diarios" style="background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #1976d2; margin-bottom: 20px;">📔 Diários Alimentares Recentes</h2>
            
            <?php if (!empty($paciente['diarios'])): ?>
                <div style="display: grid; gap: 15px;">
                    <?php foreach (array_slice($paciente['diarios'], 0, 5) as $diario): ?>
                        <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9;">
                            <h4 style="margin: 0 0 10px 0; color: #1976d2;">
                                📅 <?php echo date('d/m/Y', strtotime($diario['data_diario'])); ?>
                            </h4>
                            <p style="margin: 0; color: #333;">
                                <?php echo htmlspecialchars($diario['descricao_diario'] ?: 'Sem descrição'); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 15px; text-align: center;">
                    <a href="/medico/paciente/<?php echo $paciente['id_paciente']; ?>/historico" 
                       style="background: #1976d2; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">
                        Ver Histórico Completo
                    </a>
                </div>
            <?php else: ?>
                <p style="color: #666;">Nenhum diário alimentar registrado.</p>
            <?php endif; ?>
        </section>

        <!-- Ações -->
        <section class="acoes" style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="color: #1976d2; margin-bottom: 20px;">⚙️ Ações</h2>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="/medico/paciente/<?php echo $paciente['id_paciente']; ?>/historico" 
                   style="background: #4caf50; color: white; padding: 12px 20px; border-radius: 5px; text-decoration: none;">
                    📋 Ver Histórico Completo
                </a>
                
                <button onclick="agendarConsulta(<?php echo $paciente['id_paciente']; ?>)" 
                        style="background: #ff9800; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    📅 Agendar Consulta
                </button>
                
                <button onclick="prescricaoMedica(<?php echo $paciente['id_paciente']; ?>)" 
                        style="background: #9c27b0; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    💊 Nova Prescrição
                </button>
            </div>
        </section>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/medico/pacientes" style="color: #1976d2; text-decoration: none; font-weight: bold;">
                ← Voltar à lista de pacientes
            </a>
        </div>
    </main>
</div>

<script>
function agendarConsulta(idPaciente) {
    // Implementar modal ou redirecionar para página de agendamento
    alert('Funcionalidade de agendamento em desenvolvimento');
}

function prescricaoMedica(idPaciente) {
    // Implementar modal ou redirecionar para página de prescrição
    alert('Funcionalidade de prescrição em desenvolvimento');
}
</script>
