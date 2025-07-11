<?php
// Inicializar sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: /usuario/login');
    exit;
}
?>

<div class="usuario-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%); min-height: 100vh;">
    <main class="usuario-main-content" style="max-width: 900px; margin: 0 auto; padding-bottom: 40px;">
        <div class="usuario-header" style="margin-bottom: 40px; background: linear-gradient(90deg, #4CAF50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.13); border-radius: 14px; padding: 25px;">
            <h1 style="font-size:2.5rem; margin-bottom: 10px; letter-spacing: 1px; color: #fff; text-shadow: 1px 2px 8px #388e3c33;">
                👤 Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['nome_usuario']); ?>!
            </h1>
            <p style="font-size:1.18rem; color:#e0ffe0; margin-bottom: 0;">
                Escolha sua área de atuação ou continue como usuário padrão.
            </p>
        </div>
        
        <!-- Seção de escolha de perfil -->
        <section class="usuario-section" id="cadastro-tipos" style="margin-bottom: 32px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #4caf5022; padding: 25px; text-align:center;">
            <h2 style="font-size:1.4rem; color:#388e3c; margin-bottom: 18px;">
                <span style="font-size:1.2em;">📝</span> Acessar como:
            </h2>
            <div style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: center;">
                <a href="/paciente/conta/entrar" style="flex:1 1 180px; min-width:180px; max-width:220px; background:#e0f7fa; border-radius:8px; box-shadow:0 1px 6px #4caf5011; padding:18px 10px; margin-bottom:10px; display:flex; flex-direction:column; align-items:center; text-decoration:none; color:#388e3c; font-weight:bold; transition:all 0.3s;">
                    <span style="font-size:2.2em; margin-bottom:8px;">🧑‍🦱</span>
                    Paciente
                </a>
                <a href="/nutricionista/conta/entrar" style="flex:1 1 180px; min-width:180px; max-width:220px; background:#e8f5e9; border-radius:8px; box-shadow:0 1px 6px #43a04711; padding:18px 10px; margin-bottom:10px; display:flex; flex-direction:column; align-items:center; text-decoration:none; color:#43a047; font-weight:bold; transition:all 0.3s;">
                    <span style="font-size:2.2em; margin-bottom:8px;">🥗</span>
                    Nutricionista
                </a>
                <a href="/medico/conta/entrar" style="flex:1 1 180px; min-width:180px; max-width:220px; background:#e3f2fd; border-radius:8px; box-shadow:0 1px 6px #1976d211; padding:18px 10px; margin-bottom:10px; display:flex; flex-direction:column; align-items:center; text-decoration:none; color:#1976d2; font-weight:bold; transition:all 0.3s;">
                    <span style="font-size:2.2em; margin-bottom:8px;">🩺</span>
                    Médico
                </a>
            </div>
        </section>

        <!-- Informações da conta -->
        <section class="usuario-section" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #4caf5022; padding: 25px; margin-bottom: 25px;">
            <h2 style="color: #4caf50; margin-bottom: 20px; font-size: 1.4rem;">
                ⚙️ Configurações da Conta
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="/conta" style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-decoration: none; color: #495057; border: 1px solid #dee2e6; transition: all 0.3s; text-align: center;">
                    📝 Editar Perfil
                </a>
                <a href="/usuario/conta/sair" style="background: #f8d7da; padding: 15px; border-radius: 8px; text-decoration: none; color: #721c24; border: 1px solid #f5c6cb; transition: all 0.3s; text-align: center;">
                    🚪 Sair
                </a>
            </div>
        </section>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('a[style*="background:#e0f7fa"], a[style*="background:#e8f5e9"], a[style*="background:#e3f2fd"]');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(76,175,80,0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 1px 6px #4caf5011';
        });
    });

    // Adicionar efeito hover para botões de configuração
    const configCards = document.querySelectorAll('a[href="/conta"], a[href="/usuario/conta/sair"]');
    
    configCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
            this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>