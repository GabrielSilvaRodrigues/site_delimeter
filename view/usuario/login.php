<?php
// Inicializar sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se usuário já está logado, redirecionar
if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    header('Location: /usuario');
    exit;
}
?>

<div class="login-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <main class="login-main-content" style="max-width: 400px; width: 100%; padding: 20px;">
        <div class="login-form-container" style="background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 16px rgba(76,175,80,0.15);">
            <h1 style="text-align: center; color: #4caf50; margin-bottom: 30px; font-size: 2rem;">
                🔐 Login
            </h1>
            
            <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>
            
            <form id="loginForm" method="POST" action="/login/usuario">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Email:</label>
                    <input type="email" id="email_usuario" name="email_usuario" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="senha_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Senha:</label>
                    <input type="password" id="senha_usuario" name="senha_usuario" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-bottom: 15px;">
                    Entrar
                </button>
            </form>
            
            <div style="text-align: center;">
                <p style="margin: 10px 0; color: #666;">Não tem uma conta?</p>
                <a href="/usuario/cadastro" style="color: #4caf50; text-decoration: none; font-weight: bold;">Cadastre-se aqui</a>
            </div>
        </div>
    </main>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const email = formData.get('email_usuario');
    const senha = formData.get('senha_usuario');
    
    // Validação básica
    if (!email || !senha) {
        showMessage('Por favor, preencha todos os campos.', 'error');
        return;
    }
    
    // Mostrar loading
    showMessage('Fazendo login...', 'info');
    
    console.log('Enviando dados de login:', { email: email, senha: senha ? '***' : 'vazio' });
    
    fetch('/login/usuario', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(formData)
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        return response.text().then(text => {
            console.log('Response text:', text);
            
            // Tentar parsear como JSON
            try {
                const data = JSON.parse(text);
                return { data, status: response.status, ok: response.ok };
            } catch (e) {
                console.error('Erro ao parsear JSON:', e);
                console.error('Texto recebido:', text.substring(0, 500));
                
                // Se não é JSON, pode ser um redirecionamento ou erro HTML
                if (text.includes('<!DOCTYPE') || text.includes('<html')) {
                    // Servidor retornou HTML - provavelmente erro 500 ou redirecionamento
                    throw new Error('Servidor retornou HTML. Verifique os logs do servidor.');
                } else {
                    throw new Error('Resposta inválida do servidor: ' + text.substring(0, 100));
                }
            }
        });
    })
    .then(({ data, status, ok }) => {
        if (!ok) {
            throw new Error(data.error || `HTTP ${status}: Erro no servidor`);
        }
        
        console.log('Response data:', data);
        
        if (data.success) {
            showMessage('Login realizado com sucesso! Redirecionando...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/usuario';
            }, 1000);
        } else {
            showMessage(data.error || 'Erro ao fazer login.', 'error');
        }
    })
    .catch(error => {
        console.error('Erro completo:', error);
        console.error('Stack trace:', error.stack);
        
        let errorMessage = 'Erro desconhecido.';
        
        if (error.message.includes('Failed to fetch')) {
            errorMessage = 'Erro de rede. Verifique sua conexão com a internet.';
        } else if (error.message.includes('HTML')) {
            errorMessage = 'Erro interno do servidor. Verifique os logs.';
        } else if (error.message.includes('JSON')) {
            errorMessage = 'Erro de comunicação com o servidor. Contate o suporte.';
        } else {
            errorMessage = error.message;
        }
        
        showMessage(errorMessage, 'error');
    });
});

// Preencher com dados da URL se houver erro
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    if (error) {
        let message = 'Erro no login.';
        switch (error) {
            case 'missing_data':
                message = 'Por favor, preencha todos os campos.';
                break;
            case 'invalid_credentials':
                message = 'Email ou senha incorretos.';
                break;
            case 'server_error':
                message = 'Erro interno do servidor. Tente novamente.';
                break;
        }
        showMessage(message, 'error');
    }
});

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.style.display = 'block';
    
    if (type === 'success') {
        messageDiv.style.backgroundColor = '#d4edda';
        messageDiv.style.color = '#155724';
        messageDiv.style.border = '1px solid #c3e6cb';
    } else if (type === 'info') {
        messageDiv.style.backgroundColor = '#d1ecf1';
        messageDiv.style.color = '#0c5460';
        messageDiv.style.border = '1px solid #bee5eb';
    } else {
        messageDiv.style.backgroundColor = '#f8d7da';
        messageDiv.style.color = '#721c24';
        messageDiv.style.border = '1px solid #f5c6cb';
    }
}
</script>