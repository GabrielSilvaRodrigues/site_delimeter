import React from "react";

function FormUsuario() {
  return (
    <main>
      <div className="container-calc">
        <form action="/api/usuario" method="POST" id="formulario">
          <div className="container">
            <h1>Cadastro de Usuário</h1>
            <div className="form-group">
              <label htmlFor="nome_usuario">Nome:</label>
              <input type="text" name="nome_usuario" required id="nome_usuario" />
            </div>
            <div className="form-group">
              <label htmlFor="email_usuario">Email:</label>
              <input type="email" name="email_usuario" required id="email_usuario" />
            </div>
            <div className="form-group">
              <label htmlFor="senha_usuario">Senha:</label>
              <input type="password" name="senha_usuario" required id="senha_usuario" />
            </div>
            <div className="form-group">
              <label htmlFor="confirmar_senha">Confirmar Senha:</label>
              <input type="password" name="confirmar_senha" required id="confirmar_senha" />
            </div>
            <button type="submit">Cadastrar</button>
          </div>
        </form>
      </div>
    </main>
  );
}

export default FormUsuario;
