<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header('Location: /usuario/login');
    exit;
}

$tipo = $usuario['tipo'] ?? 'usuario'; // 'usuario', 'paciente', 'nutricionista', 'medico'

// Dados antropométricos para pacientes
$dadosAntropometricos = $_SESSION['dados_antropometricos'] ?? [];

// Definindo rotas exatamente conforme mapeado em seus Routes e Controllers
switch ($tipo) {
    case 'paciente':
        $rotaAtualizar = '/paciente/conta/atualizar';
        $rotaDeletar   = '/paciente/conta/deletar';
        $rotaSair      = '/paciente/conta/sair';
        break;
    case 'nutricionista':
        $rotaAtualizar = '/nutricionista/conta/atualizar';
        $rotaDeletar   = '/nutricionista/conta/deletar';
        $rotaSair      = '/nutricionista/conta/sair';
        break;
    case 'medico':
        $rotaAtualizar = '/medico/conta/atualizar';
        $rotaDeletar   = '/medico/conta/deletar';
        $rotaSair      = '/medico/conta/sair';
        break;
    case 'usuario':
    default:
        $rotaAtualizar = '/conta/atualizar';
        $rotaDeletar   = '/conta/deletar';
        $rotaSair      = '/conta/sair';
}
?>

<main>
    <div class="container-calc">
        <div class="container">
            <h1>Minha Conta</h1>
            <?php if (isset($_GET['atualizado'])): ?>
                <div style="color:green; margin-bottom:10px;">Dados atualizados com sucesso!</div>
            <?php elseif (isset($_GET['erro'])): ?>
                <div style="color:red; margin-bottom:10px;">Erro ao atualizar dados.</div>
            <?php endif; ?>
            <form action="/conta/atualizar" method="POST" id="formulario-conta">
                <input type="hidden" name="tipo_form" value="usuario">
                <div class="form-group">
                    <label for="nome_usuario">Nome:</label>
                    <input type="text" name="nome_usuario" required id="nome_usuario" value="<?php echo htmlspecialchars($usuario['nome_usuario'] ?? $usuario['nome'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="email_usuario">Email:</label>
                    <input type="email" name="email_usuario" required id="email_usuario" value="<?php echo htmlspecialchars($usuario['email_usuario'] ?? $usuario['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="senha_usuario">Nova Senha:</label>
                    <input type="password" name="senha_usuario" id="senha_usuario" placeholder="Deixe em branco para não alterar">
                </div>
                <button type="submit">Atualizar Dados</button>
            </form>
            <form action="/conta/deletar" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não poderá ser desfeita!');" style="margin-top:20px;">
                <button type="submit" style="background:#c62828;">Deletar Conta</button>
            </form>
            <a href="/conta/sair" style="display:inline-block; margin-top:20px; color:#fff; background:#388e3c; padding:10px 24px; border-radius:4px; text-decoration:none;">Sair</a>
            <?php if ($tipo === 'paciente'): ?>
                <form action="<?php echo $rotaAtualizar; ?>" method="POST" id="formulario-paciente" style="margin-top: 24px;">
                    <input type="hidden" name="tipo_form" value="paciente">
                    <h2>Dados do Paciente</h2>
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" name="cpf" id="cpf" required value="<?php echo htmlspecialchars($usuario['cpf'] ?? $usuario['cpf_paciente'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="nis">NIS:</label>
                        <input type="text" name="nis" id="nis" value="<?php echo htmlspecialchars($usuario['nis'] ?? $usuario['nis_paciente'] ?? ''); ?>">
                    </div>
                    <button type="submit">Atualizar Dados do Paciente</button>
                </form>
                
                <?php if (!empty($dadosAntropometricos)): ?>
                    <div style="margin-top: 24px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                        <h2>Dados Antropométricos Atuais</h2>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                            <?php if (isset($dadosAntropometricos['sexo_paciente']) && $dadosAntropometricos['sexo_paciente'] !== ''): ?>
                                <div>
                                    <strong>Sexo:</strong> 
                                    <?php echo $dadosAntropometricos['sexo_paciente'] == '1' ? 'Masculino' : 'Feminino'; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($dadosAntropometricos['altura_paciente'])): ?>
                                <div>
                                    <strong>Altura:</strong> 
                                    <?php echo htmlspecialchars($dadosAntropometricos['altura_paciente']); ?>m
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($dadosAntropometricos['peso_paciente'])): ?>
                                <div>
                                    <strong>Peso:</strong> 
                                    <?php echo htmlspecialchars($dadosAntropometricos['peso_paciente']); ?>kg
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($dadosAntropometricos['imc'])): ?>
                                <div>
                                    <strong>IMC:</strong> 
                                    <?php echo number_format($dadosAntropometricos['imc'], 2); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($dadosAntropometricos['classificacao_imc'])): ?>
                                <div>
                                    <strong>Classificação:</strong> 
                                    <?php echo htmlspecialchars($dadosAntropometricos['classificacao_imc']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($dadosAntropometricos['data_medida'])): ?>
                                <div>
                                    <strong>Última Medição:</strong> 
                                    <?php echo date('d/m/Y', strtotime($dadosAntropometricos['data_medida'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div style="margin-top: 15px;">
                            <a href="/paciente/dados-antropometricos" style="display: inline-block; background: #007bff; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px;">
                                📊 Gerenciar Dados Antropométricos
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form action="/paciente/conta/deletar" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não poderá ser desfeita!');" style="margin-top:20px;">
                    <button type="submit" style="background:#c62828;">Deletar Conta</button>
                </form>
                <a href="/paciente/conta/sair" style="display:inline-block; margin-top:20px; color:#fff; background:#388e3c; padding:10px 24px; border-radius:4px; text-decoration:none;">Sair</a>
            <?php elseif ($tipo === 'nutricionista'): ?>
                <form action="<?php echo $rotaAtualizar; ?>" method="POST" id="formulario-nutricionista" style="margin-top: 24px;">
                    <input type="hidden" name="tipo_form" value="nutricionista">
                    <h2>Dados do Nutricionista</h2>
                    <div class="form-group">
                        <label for="crm_nutricionista">CRN:</label>
                        <input type="text" name="crm_nutricionista" id="crm_nutricionista" required value="<?php echo htmlspecialchars($usuario['crm_nutricionista'] ?? $usuario['crm'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="cpf_nutricionista">CPF:</label>
                        <input type="text" name="cpf" id="cpf_nutricionista" required value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>">
                    </div>
                    <button type="submit">Atualizar Dados do Nutricionista</button>
                </form>
                <form action="/nutricionista/conta/deletar" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não poderá ser desfeita!');" style="margin-top:20px;">
                    <button type="submit" style="background:#c62828;">Deletar Conta</button>
                </form>
                <a href="/nutricionista/conta/sair" style="display:inline-block; margin-top:20px; color:#fff; background:#388e3c; padding:10px 24px; border-radius:4px; text-decoration:none;">Sair</a>
            <?php elseif ($tipo === 'medico'): ?>
                <form action="<?php echo $rotaAtualizar; ?>" method="POST" id="formulario-medico" style="margin-top: 24px;">
                    <input type="hidden" name="tipo_form" value="medico">
                    <h2>Dados do Médico</h2>
                    <div class="form-group">
                        <label for="crm_medico">CRM:</label>
                        <input type="text" name="crm_medico" id="crm_medico" required value="<?php echo htmlspecialchars($usuario['crm_medico'] ?? $usuario['crm'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="cpf_medico">CPF:</label>
                        <input type="text" name="cpf" id="cpf_medico" required value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>">
                    </div>
                    <button type="submit">Atualizar Dados do Médico</button>
                </form>
                <form action="/medico/conta/deletar" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não poderá ser desfeita!');" style="margin-top:20px;">
                    <button type="submit" style="background:#c62828;">Deletar Conta</button>
                </form>
                <a href="/medico/conta/sair" style="display:inline-block; margin-top:20px; color:#fff; background:#388e3c; padding:10px 24px; border-radius:4px; text-decoration:none;">Sair</a>
            <?php endif; ?>
        </div>
    </div>
</main>