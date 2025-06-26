    <h1>Cadastro de Paciente</h1>
    <form action="/api/paciente" method="post">
        <label for="id_usuario">ID Usuário:</label>
        <input type="number" id="id_usuario" name="id_usuario" required><br><br>

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required pattern="\d{11}" maxlength="11"><br><br>

        <label for="nis">NIS:</label>
        <input type="text" id="nis" name="nis" required pattern="\d{11}" maxlength="11"><br><br>

        <button type="submit">Salvar</button>
    </form>