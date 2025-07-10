<main>
    <div class="container-calc">
        <form action="/usuario/entrar" method="POST" id="formulario">
            <div class="container">
                <h1>Entrar</h1>
                
                <?php if (isset($_GET['error'])): ?>
                    <div style="color: red; margin-bottom: 10px; text-align: center;">
                        <?php 
                        switch($_GET['error']) {
                            case 'missing_data':
                                echo 'Por favor, preencha todos os campos.';
                                break;
                            case 'invalid_credentials':
                                echo 'Email ou senha incorretos.';
                                break;
                            default:
                                echo 'Erro no login.';
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="email_usuario">Email:</label>
                    <input type="email" name="email_usuario" required id="email_usuario">
                </div>
                <div class="form-group">
                    <label for="senha_usuario">Senha:</label>
                    <input type="password" name="senha_usuario" required id="senha_usuario">
                </div>
                <button type="submit">Entrar</button>
                
                <div style="text-align: center; margin-top: 15px;">
                    <a href="/usuario/cadastro" style="color: #007bff; text-decoration: none;">
                        Não tem conta? Cadastre-se aqui
                    </a>
                </div>
            </div>
        </form>
    </div>
</main>