<main>
    <div class="container-calc">
        <form id="formulario" method="POST" action="/api/paciente">
            <div class="container">
                <h2 style="text-align:center;margin-bottom:20px;">Cadastro de Paciente</h2>
                
                <div id="message" style="display:none; margin-bottom:15px; padding:10px; border-radius:5px;"></div>
                
                <div class="form-group">
                    <label for="cpf">CPF *:</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required maxlength="14">
                </div>
                <div class="form-group">
                    <label for="nis">NIS (opcional):</label>
                    <input type="text" id="nis" name="nis" placeholder="000.00000.00-0" maxlength="14">
                </div>
                <button type="submit" style="margin-top:18px;">Cadastrar Paciente</button>
            </div>
        </form>
    </div>
</main>

<script>
// Máscara para CPF
document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        e.target.value = value;
    }
});

// Máscara para NIS
document.getElementById('nis').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d{5})(\d{2})(\d{1})/, '$1.$2.$3-$4');
        e.target.value = value;
    }
});

</script>
