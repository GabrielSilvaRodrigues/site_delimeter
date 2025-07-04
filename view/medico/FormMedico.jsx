import React from 'react';

function FormMedico() {
    return (
        <main>
            <div className="container-calc">
                <form id="formulario" method="POST" action="/api/medico">
                    <div className="container">
                        <h2 style={{ textAlign: 'center', marginBottom: '20px' }}>Cadastro de Médico</h2>
                        <div className="form-group">
                            <label htmlFor="crm_medico">CRM:</label>
                            <input type="text" id="crm_medico" name="crm_medico" required />
                        </div>
                        <div className="form-group">
                            <label htmlFor="cpf">CPF:</label>
                            <input type="text" id="cpf" name="cpf" required />
                        </div>
                        <button type="submit" style={{ marginTop: '18px' }}>Cadastrar</button>
                    </div>
                </form>
            </div>
        </main>
    );
}

export default FormMedico;