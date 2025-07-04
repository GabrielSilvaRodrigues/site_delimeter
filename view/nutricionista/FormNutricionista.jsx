```jsx
import React from 'react';

function FormNutricionista() {
    return (
        <main>
            <div className="container-calc">
                <form id="formulario" method="POST" action="/api/nutricionista">
                    <div className="container">
                        <h2 style={{ textAlign: 'center', marginBottom: '20px' }}>Cadastro de Nutricionista</h2>
                        <div className="form-group">
                            <label htmlFor="crm_nutricionista">CRM:</label>
                            <input type="text" id="crm_nutricionista" name="crm_nutricionista" required />
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

export default FormNutricionista;
```
