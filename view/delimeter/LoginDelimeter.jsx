import React from "react";

function LoginDelimeter() {
  return (
    <main>
      <div className="container-calc">
        <form action="select_usuario.php" method="POST" id="formulario">
          <div className="container">
            <h1>Entrar</h1>
            <div className="form-group">
              <label htmlFor="email_usuario">Email:</label>
              <input type="email" name="email_usuario" required id="email_usuario" />
            </div>
            <div className="form-group">
              <label htmlFor="senha_usuario">Senha:</label>
              <input type="password" name="senha_usuario" required id="senha_usuario" />
            </div>
            <button type="submit">Entrar</button>
          </div>
        </form>
      </div>
    </main>
  );
}

export default LoginDelimeter;
