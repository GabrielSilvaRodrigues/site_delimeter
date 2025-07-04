import React, { useState } from 'react';

function FormMedico() {
    const [crm, setCrm] = useState('');
    const [cpf, setCpf] = useState('');

    function handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('crm_medico', crm);
        formData.append('cpf', cpf);

        fetch('/medico/cadastro', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cadastro realizado com sucesso!');
                // Redirecionar ou realizar outra ação
            } else {
                alert('Erro: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Erro ao cadastrar médico:', error);
            alert('Erro ao cadastrar médico. Tente novamente mais tarde.');
        });
    }

    return (
        <main>
            <div className="container-calc">
                <form id="formulario" method="POST" onSubmit={handleSubmit}>
                    <div className="container">
                        <h2 style={{ textAlign: 'center', marginBottom: '20px' }}>Cadastro de Médico</h2>
                        <div className="form-group">
                            <label htmlFor="crm_medico">CRM:</label>
                            <input 
                                type="text" 
                                id="crm_medico" 
                                name="crm_medico" 
                                required 
                                value={crm}
                                onChange={e => setCrm(e.target.value)}
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="cpf">CPF:</label>
                            <input 
                                type="text" 
                                id="cpf" 
                                name="cpf" 
                                required 
                                value={cpf}
                                onChange={e => setCpf(e.target.value)}
                            />
                        </div>
                        <button type="submit" style={{ marginTop: '18px' }}>Cadastrar</button>
                    </div>
                </form>
            </div>
        </main>
    );
}

export default FormMedico;