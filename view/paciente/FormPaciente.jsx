import React, { useState } from "react";

function FormPaciente() {
  const [cpf, setCpf] = useState("");
  const [nis, setNis] = useState("");

  function handleSubmit(e) {
    e.preventDefault();
    // Lógica para enviar os dados para o servidor
    const data = { cpf, nis };
    console.log("Enviar dados:", data);
    // Aqui você pode chamar uma função para enviar os dados via fetch/Axios, etc.
  }

  return (
    <main>
      <div className="container-calc">
        <form id="formulario" method="POST" action="/paciente/cadastro" onSubmit={handleSubmit}>
          <div className="container">
            <h2 style={{ textAlign: "center", marginBottom: "20px" }}>Cadastro de Paciente</h2>
            <div className="form-group">
              <label htmlFor="cpf">CPF:</label>
              <input
                type="text"
                id="cpf"
                name="cpf"
                required
                value={cpf}
                onChange={(e) => setCpf(e.target.value)}
              />
            </div>
            <div className="form-group">
              <label htmlFor="nis">NIS:</label>
              <input
                type="text"
                id="nis"
                name="nis"
                value={nis}
                onChange={(e) => setNis(e.target.value)}
              />
            </div>
            <button type="submit" style={{ marginTop: "18px" }}>Cadastrar</button>
          </div>
        </form>
      </div>
    </main>
  );
}

export default FormPaciente;