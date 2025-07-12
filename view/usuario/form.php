<div class="cadastro-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <main class="cadastro-main-content" style="max-width: 400px; width: 100%; padding: 20px;">
        <div class="cadastro-form-container" style="background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 16px rgba(76,175,80,0.15);">
            <h1 style="text-align: center; color: #4caf50; margin-bottom: 30px; font-size: 2rem;">
                📝 Cadastro de Usuário
            </h1>
            
            <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>
            
            <form id="cadastroForm" method="POST" action="/api/usuario">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="nome_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Nome completo:</label>
                    <input type="text" id="nome_usuario" name="nome_usuario" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Email:</label>
                    <input type="email" id="email_usuario" name="email_usuario" required 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="senha_usuario" style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Senha:</label>
                    <input type="password" id="senha_usuario" name="senha_usuario" required minlength="6"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-bottom: 15px;">
                    Cadastrar
                </button>
            </form>
            
            <div style="text-align: center;">
                <p style="margin: 10px 0; color: #666;">Já tem uma conta?</p>
                <a href="/usuario/login" style="color: #4caf50; text-decoration: none; font-weight: bold;">Faça login aqui</a>
            </div>
        </div>
    </main>
</div>