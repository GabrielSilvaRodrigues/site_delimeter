import React, { useState } from 'react';

function FormNutricionista() {
    const [crmNutricionista, setCrmNutricionista] = useState('');
    const [cpf, setCpf] = useState('');
    const [mensagem, setMensagem] = useState('');

    function handleSubmit(event) {
        event.preventDefault();
        setMensagem('');

        if (!crmNutricionista || !cpf) {
            setMensagem('Por favor, preencha todos os campos obrigatórios.');
            return;
        }

        const dados = { crm_nutricionista: crmNutricionista, cpf: cpf };

        fetch('/api/nutricionista', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dados),
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    setMensagem(data.error);
                } else {
                    setMensagem('Nutricionista cadastrado com sucesso!');
                    setCrmNutricionista('');
                    setCpf('');
                }
            })
            .catch(error => {
                setMensagem('Erro ao cadastrar nutricionista. Tente novamente mais tarde.');
                console.error('Erro:', error);
            });
    }

    return (
        <main>
            <div className="container-calc">
                <form id="formulario" onSubmit={handleSubmit}>
                    <div className="container">
                        <h2 style={{ textAlign: 'center', marginBottom: '20px' }}>Cadastro de Nutricionista</h2>
                        <div className="form-group">
                            <label htmlFor="crm_nutricionista">CRM:</label>
                            <input
                                type="text"
                                id="crm_nutricionista"
                                name="crm_nutricionista"
                                value={crmNutricionista}
                                onChange={e => setCrmNutricionista(e.target.value)}
                                required
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="cpf">CPF:</label>
                            <input
                                type="text"
                                id="cpf"
                                name="cpf"
                                value={cpf}
                                onChange={e => setCpf(e.target.value)}
                                required
                            />
                        </div>
                        <button type="submit" style={{ marginTop: '18px' }}>Cadastrar</button>
                        {mensagem && <p style={{ marginTop: '10px', color: 'red' }}>{mensagem}</p>}
                    </div>
                </form>
            </div>
        </main>
    );
}

export default FormNutricionista;