import React from "react";

function FormPaciente() {
  return (
    <main>
      <div className="container-calc">
        <form id="formulario" method="POST" action="/api/paciente">
          <div className="container">
            <h2 style={{ textAlign: "center", marginBottom: "20px" }}>Cadastro de Paciente</h2>
            <div className="form-group">
              <label htmlFor="cpf">CPF:</label>
              <input type="text" id="cpf" name="cpf" required />
            </div>
            <div className="form-group">
              <label htmlFor="nis">NIS:</label>
              <input type="text" id="nis" name="nis" />
            </div>
            <button type="submit" style={{ marginTop: "18px" }}>Cadastrar</button>
          </div>
        </form>
      </div>
    </main>
  );
}

export default FormPaciente;