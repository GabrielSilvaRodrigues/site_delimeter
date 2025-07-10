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

// Envio do formulário
document.getElementById('formulario').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const cpf = document.getElementById('cpf').value.replace(/\D/g, '');
    const nis = document.getElementById('nis').value.replace(/\D/g, '');
    
    // Validações
    if (cpf.length !== 11) {
        showMessage('CPF deve ter 11 dígitos.', 'error');
        return;
    }
    
    if (nis && nis.length !== 11) {
        showMessage('NIS deve ter 11 dígitos.', 'error');
        return;
    }
    
    // Enviar dados
    const formData = new FormData();
    formData.append('cpf', cpf);
    if (nis) formData.append('nis', nis);
    
    fetch('/api/paciente', {
        method: 'POST',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Cadastro realizado com sucesso! Redirecionando...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/paciente';
            }, 1500);
        } else {
            showMessage(data.error || 'Erro ao cadastrar paciente.', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showMessage('Erro ao processar solicitação.', 'error');
    });
});

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.style.display = 'block';
    messageDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
    messageDiv.style.color = type === 'success' ? '#155724' : '#721c24';
    messageDiv.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
}
</script>