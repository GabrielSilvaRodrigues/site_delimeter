    <main>
        <div class="container-calc">
            <form id="formulario" method="POST" action="/api/paciente">
                <div class="container">
                    <h2 style="text-align:center;margin-bottom:20px;">Cadastro de Paciente</h2>
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" required>
                    </div>
                    <div class="form-group">
                        <label for="nis">NIS:</label>
                        <input type="text" id="nis" name="nis">
                    </div>
                    <button type="submit" style="margin-top:18px;">Cadastrar</button>
                </div>
            </form>
        </div>
    </main>