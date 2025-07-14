<main>
    <div class="container-calc">
        <form id="formulario" method="POST" action="/api/nutricionista">
            <div class="container">
                <h2 style="text-align:center;margin-bottom:20px; color:#4caf50;">Cadastro de Nutricionista</h2>
                
                <!-- Mensagem de feedback -->
                <div id="message" style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 5px;"></div>
                
                <div class="form-group">
                    <label for="crm_nutricionista">CRN (Conselho Regional de Nutricionistas):</label>
                    <input type="text" id="crm_nutricionista" name="crm_nutricionista" placeholder="Ex: 12345/SP" required>
                    <small style="color: #666;">Digite o número do CRN com UF (ex: 12345/SP)</small>
                </div>
                
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                    <small style="color: #666;">Digite apenas números ou formato XXX.XXX.XXX-XX</small>
                </div>
                
                <div class="form-actions" style="display: flex; gap: 10px; justify-content: center; margin-top: 25px;">
                    <button type="button" onclick="history.back()" style="background: #9e9e9e; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" style="background: #4caf50; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer;">
                        Cadastrar Nutricionista
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- Incluir scripts das classes -->
<script src="/public/scripts/classes/ApiClient.js"></script>
<script src="/public/scripts/classes/MessageManager.js"></script>
<script src="/public/scripts/classes/PerfilManager.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiClient = new ApiClient();
    const messageManager = new MessageManager();
    const nutricionistaManager = new NutricionistaPerfilManager(apiClient, messageManager);
    
    // Event listener para o formulário
    document.getElementById('formulario').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const dados = Object.fromEntries(formData.entries());
        
        // Validação básica
        if (!dados.crm_nutricionista || !dados.cpf) {
            messageManager.error('Todos os campos são obrigatórios.');
            return;
        }
        
        try {
            const response = await apiClient.post('/api/nutricionista/criar', dados);
            
            if (response.success) {
                messageManager.success('Nutricionista cadastrado com sucesso!');
                setTimeout(() => {
                    window.location.href = '/nutricionista';
                }, 2000);
            } else {
                messageManager.error(response.error || 'Erro ao cadastrar nutricionista.');
            }
        } catch (error) {
            console.error('Erro ao cadastrar nutricionista:', error);
            messageManager.error('Erro de conexão. Tente novamente.');
        }
    });
    
    // Formatação de CPF
    document.getElementById('cpf').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            e.target.value = value;
        }
    });
});
</script>
