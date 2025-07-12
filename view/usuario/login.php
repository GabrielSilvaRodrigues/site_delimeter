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