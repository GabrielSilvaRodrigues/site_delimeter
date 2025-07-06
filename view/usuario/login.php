<?php
// Iniciar sessão apenas se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se já estiver logado, redirecionar para o painel apropriado
if (isset($_SESSION['usuario'])) {
    $tipo = $_SESSION['usuario']['tipo'] ?? 'usuario';
    header("Location: /$tipo");
    exit;
}

// Incluir o header se estiver em modo standalone, caso contrário não incluir
$incluirHeader = !isset($_GET['standalone']) || $_GET['standalone'] !== 'true';

if ($incluirHeader) {
    include_once dirname(__DIR__) . '/includes/header.php';
}
?>

<div class="login-container" style="min-height: <?php echo $incluirHeader ? 'calc(100vh - 80px)' : '100vh'; ?>; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; padding: 20px; <?php echo $incluirHeader ? 'margin-top: 0;' : ''; ?>">
    <div class="login-box" style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); width: 100%; max-width: 400px; overflow: hidden;">
        
        <!-- Header do formulário -->
        <div class="login-header" style="background: linear-gradient(45deg, #667eea, #764ba2); padding: 40px 30px; text-align: center;">
            <div class="logo-container" style="margin-bottom: 20px;">
                <img src="/public/assets/images/logo.png" alt="Delimeter Logo" style="height: 60px; filter: brightness(0) invert(1);">
            </div>
            <h1 style="color: white; margin: 0; font-size: 2rem; font-weight: 300; letter-spacing: 1px;">
                Bem-vindo de volta
            </h1>
            <p style="color: rgba(255,255,255,0.8); margin: 10px 0 0 0; font-size: 1rem;">
                Entre em sua conta para continuar
            </p>
        </div>

        <!-- Formulário de login -->
        <div class="login-form" style="padding: 40px 30px;">
            <form action="/login/usuario" method="POST" id="loginForm">
                <div class="form-group" style="margin-bottom: 25px; position: relative;">
                    <label for="email_usuario" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        Email
                    </label>
                    <div class="input-container" style="position: relative;">
                        <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; font-size: 1.1rem;">📧</span>
                        <input 
                            type="email" 
                            name="email_usuario" 
                            id="email_usuario" 
                            required 
                            style="width: 100%; padding: 15px 15px 15px 45px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; box-sizing: border-box;"
                            placeholder="seu@email.com"
                            onFocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                            onBlur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none'"
                            onInvalid="this.style.borderColor='#ff6b6b'; this.style.boxShadow='0 0 0 3px rgba(255,107,107,0.1)'"
                            onInput="if(this.checkValidity()) { this.style.borderColor='#51cf66'; this.style.boxShadow='0 0 0 3px rgba(81,207,102,0.1)'; } else { this.style.borderColor='#e1e5e9'; this.style.boxShadow='none'; }"
                        >
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 30px; position: relative;">
                    <label for="senha_usuario" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        Senha
                    </label>
                    <div class="input-container" style="position: relative;">
                        <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; font-size: 1.1rem;">🔒</span>
                        <input 
                            type="password" 
                            name="senha_usuario" 
                            id="senha_usuario" 
                            required 
                            minlength="6"
                            style="width: 100%; padding: 15px 15px 15px 45px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; box-sizing: border-box;"
                            placeholder="Sua senha"
                            onFocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                            onBlur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none'"
                            onInput="if(this.value.length >= 6) { this.style.borderColor='#51cf66'; this.style.boxShadow='0 0 0 3px rgba(81,207,102,0.1)'; } else if(this.value.length > 0) { this.style.borderColor='#ffd43b'; this.style.boxShadow='0 0 0 3px rgba(255,212,59,0.1)'; } else { this.style.borderColor='#e1e5e9'; this.style.boxShadow='none'; }"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()" 
                            style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #999; cursor: pointer; font-size: 1.1rem;"
                            title="Mostrar/Ocultar senha"
                        >
                            👁️
                        </button>
                    </div>
                </div>

                <!-- Mensagem de erro -->
                <div id="errorMessage" style="display: none; background: linear-gradient(135deg, #fee 0%, #fdd 100%); border: 1px solid #fcc; color: #c33; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; position: relative;">
                    <div style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 1.2rem; color: #c33;" onclick="clearError()" title="Fechar">✕</div>
                    <div id="errorText"></div>
                </div>

                <!-- Mensagem de sucesso -->
                <div id="successMessage" style="display: none; background: linear-gradient(135deg, #efe 0%, #dfd 100%); border: 1px solid #cfc; color: #363; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; position: relative;">
                    <div style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 1.2rem; color: #363;" onclick="clearSuccess()" title="Fechar">✕</div>
                    <div id="successText"></div>
                </div>

                <!-- Botão de login -->
                <button 
                    type="submit" 
                    style="width: 100%; background: linear-gradient(45deg, #667eea, #764ba2); color: white; border: none; padding: 15px; border-radius: 10px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;"
                    onMouseOver="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 30px rgba(102,126,234,0.3)'"
                    onMouseOut="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    Entrar na Conta
                </button>

                <!-- Links auxiliares -->
                <div style="text-align: center; margin-top: 25px;">
                    <a href="#" onclick="alert('Funcionalidade será implementada em breve!')" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">
                        Esqueceu sua senha?
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer do formulário -->
        <div class="login-footer" style="background: #f8f9fa; padding: 25px 30px; text-align: center; border-top: 1px solid #e1e5e9;">
            <p style="margin: 0 0 15px 0; color: #666; font-size: 0.9rem;">
                Ainda não tem uma conta?
            </p>
            <a 
                href="/usuario/cadastro" 
                style="display: inline-block; background: transparent; color: #667eea; border: 2px solid #667eea; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; font-size: 0.9rem;"
                onMouseOver="this.style.background='#667eea'; this.style.color='white'"
                onMouseOut="this.style.background='transparent'; this.style.color='#667eea'"
            >
                Criar Nova Conta
            </a>
        </div>
    </div>
</div>

<!-- Versão móvel - ajustes responsivos -->
<style>
@media (max-width: 480px) {
    .login-container {
        padding: 10px !important;
    }
    
    .login-box {
        margin: 0 !important;
        border-radius: 15px !important;
    }
    
    .login-header {
        padding: 30px 20px !important;
    }
    
    .login-header h1 {
        font-size: 1.5rem !important;
    }
    
    .login-form {
        padding: 30px 20px !important;
    }
    
    .login-footer {
        padding: 20px !important;
    }
}

/* Animações suaves */
.login-box {
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading state */
.loading {
    pointer-events: none;
    opacity: 0.7;
}

.loading::after {
    content: "⏳";
    margin-left: 10px;
}
</style>

<script>
// Função para alternar visibilidade da senha
function togglePassword() {
    const senhaInput = document.getElementById('senha_usuario');
    const toggleBtn = senhaInput.nextElementSibling;
    
    if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        toggleBtn.textContent = '🙈';
        toggleBtn.title = 'Ocultar senha';
    } else {
        senhaInput.type = 'password';
        toggleBtn.textContent = '👁️';
        toggleBtn.title = 'Mostrar senha';
    }
}

// Validação e envio do formulário
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const errorDiv = document.getElementById('errorMessage');
    const formData = new FormData(this);
    
    // Validações básicas
    const email = formData.get('email_usuario');
    const senha = formData.get('senha_usuario');
    
    if (!email || !senha) {
        showError('Por favor, preencha todos os campos.');
        return;
    }
    
    if (!isValidEmail(email)) {
        showError('Por favor, insira um email válido.');
        document.getElementById('email_usuario').focus();
        return;
    }
    
    if (senha.length < 6) {
        showError('A senha deve ter pelo menos 6 caracteres.');
        document.getElementById('senha_usuario').focus();
        return;
    }
    
    // Estado de loading
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    errorDiv.style.display = 'none';
    
    try {
        const response = await fetch('/login/usuario', {
            method: 'POST',
            body: formData
        });
        
        const responseText = await response.text();
        
        // Tentar parsear como JSON primeiro
        let responseData = null;
        try {
            responseData = JSON.parse(responseText);
        } catch (e) {
            // Se não conseguir parsear como JSON, pode ser redirecionamento HTML
            responseData = null;
        }

        if (response.ok) {
            // Se recebeu JSON com sucesso
            if (responseData && responseData.success) {
                // Login bem-sucedido via JSON
                const usuario = responseData.usuario;
                const tipo = usuario.tipo || 'usuario';
                showSuccess(`Login realizado com sucesso! Redirecionando para ${tipo}...`);
                setTimeout(() => {
                    window.location.href = `/${tipo}`;
                }, 1500);
            } 
            // Se é redirecionamento direto ou resposta HTML
            else if (response.redirected || responseText.includes('<!DOCTYPE') || responseText.includes('<html')) {
                showSuccess('Login realizado com sucesso! Redirecionando...');
                setTimeout(() => {
                    window.location.href = response.url || '/usuario';
                }, 1500);
            }
            // Se não há dados de erro, considera sucesso e vai para usuário padrão
            else if (!responseData || !responseData.error) {
                showSuccess('Login realizado com sucesso! Redirecionando...');
                setTimeout(() => {
                    window.location.href = '/usuario';
                }, 1500);
            }
            else {
                // Há dados de erro
                showError(responseData.error || 'Email ou senha incorretos.');
            }
        } else {
            // Erro HTTP
            if (responseData && responseData.error) {
                showError(responseData.error);
            } else {
                showError('Erro no servidor. Tente novamente.');
            }
        }
    } catch (error) {
        console.error('Erro no login:', error);
        showError('Erro de conexão. Verifique sua internet e tente novamente.');
    } finally {
        // Remover estado de loading
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
    }
});

// Função para mostrar erro
function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    const successDiv = document.getElementById('successMessage');
    
    // Esconder mensagem de sucesso se estiver visível
    successDiv.style.display = 'none';
    
    errorText.textContent = message;
    errorDiv.style.display = 'block';
    
    // Scroll para o erro se necessário
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Auto-hide após 10 segundos
    setTimeout(() => {
        if (errorDiv.style.display !== 'none') {
            errorDiv.style.display = 'none';
        }
    }, 10000);
}

// Função para mostrar sucesso
function showSuccess(message) {
    const successDiv = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    const errorDiv = document.getElementById('errorMessage');
    
    // Esconder mensagem de erro se estiver visível
    errorDiv.style.display = 'none';
    
    successText.textContent = message;
    successDiv.style.display = 'block';
    
    // Scroll para o sucesso se necessário
    successDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Validação de email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Limpar mensagem de erro quando o usuário começar a digitar
document.getElementById('email_usuario').addEventListener('input', clearError);
document.getElementById('senha_usuario').addEventListener('input', clearError);

function clearError() {
    const errorDiv = document.getElementById('errorMessage');
    if (errorDiv.style.display !== 'none') {
        errorDiv.style.display = 'none';
    }
}

function clearSuccess() {
    const successDiv = document.getElementById('successMessage');
    if (successDiv.style.display !== 'none') {
        successDiv.style.display = 'none';
    }
}

// Verificar se há mensagem de erro na URL (vinda do servidor)
window.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    if (error) {
        let errorMessage = 'Erro no login.';
        
        switch(error) {
            case 'invalid_credentials':
                errorMessage = 'Email ou senha incorretos.';
                break;
            case 'account_disabled':
                errorMessage = 'Sua conta está desativada. Entre em contato com o suporte.';
                break;
            case 'missing_data':
                errorMessage = 'Por favor, preencha todos os campos.';
                break;
            default:
                errorMessage = 'Erro no login. Tente novamente.';
        }
        
        showError(errorMessage);
    }
    
    // Focar no campo de email
    document.getElementById('email_usuario').focus();
});

// Adicionar funcionalidade de Enter para navegar entre campos
document.getElementById('email_usuario').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('senha_usuario').focus();
    }
});
</script>

<?php
if ($incluirHeader) {
    include_once dirname(__DIR__) . '/includes/footer.php';
}
?>