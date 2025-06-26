    <main>
        <div class="container-calc">
            <form action="/api/medico" method="POST" id="formulario">
                <div class="container">
                    <h1>Cadastro de Médico</h1>
                    <?php $id_usuario = $_SESSION['id']; ?>
                    <div class="form-group">
                        <label for="crm_medico">CRM Médico:</label>
                        <input type="text" name="crm_medico" required id="crm_medico">
                    </div>
                    <button type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </main>