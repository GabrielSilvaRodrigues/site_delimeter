<?php
if (!isset($_SESSION)) session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header('Location: /usuario/login');
    exit;
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
        </div>
    </div>
</main>
