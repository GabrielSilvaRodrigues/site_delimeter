<main>
    <div class="container-calc">
        <form action="select_usuario.php" method="POST" id="formulario">
            <div class="container">
                <h1>Entrar</h1>
                <div class="form-group">
                    <label for="email_usuario">Email:</label>
                    <input type="email" name="email_usuario" required id="email_usuario">
                </div>
                <div class="form-group">
                    <label for="senha_usuario">Senha:</label>
                    <input type="password" name="senha_usuario" required id="senha_usuario">
                </div>
                <button type="submit">Entrar</button>
            </div>
        </form>
    </div>
</main>