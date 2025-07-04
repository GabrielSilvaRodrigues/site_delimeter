import React from "react";

function ContaUsuario({ usuario }) {
  if (!usuario) {
    window.location.href = "/usuario/login";
    return null;
  }
  const tipo = usuario.tipo || "usuario";
  let rotaAtualizar, rotaDeletar, rotaSair;
  switch (tipo) {
    case "paciente":
      rotaAtualizar = "/paciente/conta/atualizar";
      rotaDeletar = "/paciente/conta/deletar";
      rotaSair = "/paciente/conta/sair";
      break;
    case "nutricionista":
      rotaAtualizar = "/nutricionista/conta/atualizar";
      rotaDeletar = "/nutricionista/conta/deletar";
      rotaSair = "/nutricionista/conta/sair";
      break;
    case "medico":
      rotaAtualizar = "/medico/conta/atualizar";
      rotaDeletar = "/medico/conta/deletar";
      rotaSair = "/medico/conta/sair";
      break;
    default:
      rotaAtualizar = "/conta/atualizar";
      rotaDeletar = "/conta/deletar";
      rotaSair = "/conta/sair";
  }
  return (
    <main>
      <div className="container-calc">
        <div className="container">
          <h1>Minha Conta</h1>
          {/* Mensagens de sucesso/erro podem ser controladas por estado */}
          <form action={rotaAtualizar} method="POST" id="formulario-conta">
            <input type="hidden" name="tipo_form" value="usuario" />
            <div className="form-group">
              <label htmlFor="nome_usuario">Nome:</label>
              <input type="text" name="nome_usuario" required id="nome_usuario" defaultValue={usuario.nome_usuario || usuario.nome || ""} />
            </div>
            <div className="form-group">
              <label htmlFor="email_usuario">Email:</label>
              <input type="email" name="email_usuario" required id="email_usuario" defaultValue={usuario.email_usuario || usuario.email || ""} />
            </div>
            <div className="form-group">
              <label htmlFor="senha_usuario">Nova Senha:</label>
              <input type="password" name="senha_usuario" id="senha_usuario" placeholder="Deixe em branco para não alterar" />
            </div>
            <button type="submit">Atualizar Dados</button>
          </form>
          <form action={rotaDeletar} method="POST" onSubmit={() => window.confirm("Tem certeza que deseja deletar sua conta? Esta ação não poderá ser desfeita!")} style={{ marginTop: 20 }}>
            <button type="submit" style={{ background: "#c62828" }}>Deletar Conta</button>
          </form>
          <a href={rotaSair} style={{ display: "inline-block", marginTop: 20, color: "#fff", background: "#388e3c", padding: "10px 24px", borderRadius: 4, textDecoration: "none" }}>Sair</a>
          {/* Adapte as seções de paciente/nutricionista/médico conforme necessário */}
        </div>
      </div>
    </main>
  );
}

export default ContaUsuario;
