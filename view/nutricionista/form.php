    <h1>Cadastro de Nutricionista</h1>
    <form action="/api/nutricionista" method="post">
        <label for="id_usuario">ID Usuário:</label>
        <input type="number" id="id_usuario" name="id_usuario" required><br><br>

        <label for="crm_nutricionista">CRM Nutricionista:</label>
        <input type="text" id="crm_nutricionista" name="crm_nutricionista" required><br><br>

        <button type="submit">Salvar</button>
    </form>