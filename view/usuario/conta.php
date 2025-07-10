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
?>

<div class="conta-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%); min-height: 100vh;">
    <main class="conta-main-content" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div class="conta-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #4CAF50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="color: #fff; margin: 0; font-size: 2rem;">
                ⚙️ Configurações da Conta
            </h1>
        </div>

        <div class="conta-form-container" style="background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 16px rgba(76,175,80,0.15);">
            <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>
            
            <form id="contaForm" method="POST" action="/conta/atualizar">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="nome_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Nome completo:</label>
                    <input type="text" id="nome_usuario" name="nome_usuario" required 
                           value="<?php echo htmlspecialchars($_SESSION['usuario']['nome_usuario']); ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Email:</label>
                    <input type="email" id="email_usuario" name="email_usuario" required 
                           value="<?php echo htmlspecialchars($_SESSION['usuario']['email_usuario']); ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="senha_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Nova senha (deixe em branco para manter a atual):</label>
                    <input type="password" id="senha_usuario" name="senha_usuario" 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: space-between; margin-top: 30px;">
                    <button type="submit" style="flex: 1; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                        💾 Salvar Alterações
                    </button>
                    <button type="button" onclick="confirmarExclusao()" style="flex: 1; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                        🗑️ Excluir Conta
                    </button>
                </div>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="/usuario" style="color: #4caf50; text-decoration: none; font-weight: bold;">
                    ← Voltar ao painel
                </a>
            </div>
        </div>
    </main>
</div>

<script>
document.getElementById('contaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/conta/atualizar', {
        method: 'POST',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Dados atualizados com sucesso!', 'success');
        } else {
            showMessage(data.error || 'Erro ao atualizar dados.', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showMessage('Erro de conexão. Tente novamente.', 'error');
    });
});

function confirmarExclusao() {
    if (confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')) {
        fetch('/conta/deletar', {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Conta excluída com sucesso.');
                window.location.href = '/delimeter';
            } else {
                showMessage(data.error || 'Erro ao excluir conta.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showMessage('Erro de conexão. Tente novamente.', 'error');
        });
    }
}

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.style.display = 'block';
    messageDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
    messageDiv.style.color = type === 'success' ? '#155724' : '#721c24';
    messageDiv.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
}
</script>