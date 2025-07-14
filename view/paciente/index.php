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

// Verificar se é paciente
if (!isset($_SESSION['paciente'])) {
    header('Location: /paciente/cadastro');
    exit;
}
?>

<div class="paciente-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e8f5e8 100%); min-height: 100vh;">
    <main class="paciente-main-content" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        <div class="paciente-header" style="margin-bottom: 30px; background: linear-gradient(90deg, #4caf50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.15); border-radius: 12px; padding: 25px;">
            <h1 style="color: #fff; margin: 0 0 10px 0; font-size: 2.2rem;">
                🧑‍🦱 Painel do Paciente
            </h1>
            <p style="color: #e8f5e8; margin: 0; font-size: 1.1rem;">
                Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['nome_usuario']); ?>! Gerencie sua saúde aqui.
            </p>
        </div>

        <div class="funcionalidades-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Dados Antropométricos -->
            <div class="funcionalidade-card" style="background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s;">
                <h3 style="color: #4caf50; margin: 0 0 15px 0; font-size: 1.2rem;">
                    📊 Dados Antropométricos
                </h3>
                <p style="color: #666; margin: 0 0 15px 0; font-size: 0.95rem;">
                    Registre e acompanhe suas medidas corporais, peso, altura e IMC.
                </p>
                <a href="/paciente/dados-antropometricos" style="background: #4caf50; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block;">
                    Acessar
                </a>
            </div>

            <!-- Diário de Alimentos -->
            <div class="funcionalidade-card" style="background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s;">
                <h3 style="color: #4caf50; margin: 0 0 15px 0; font-size: 1.2rem;">
                    🍎 Diário de Alimentos
                </h3>
                <p style="color: #666; margin: 0 0 15px 0; font-size: 0.95rem;">
                    Registre sua alimentação diária e monitore sua nutrição.
                </p>
                <a href="/paciente/diario-alimentos" style="background: #4caf50; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block;">
                    Acessar
                </a>
            </div>

            <!-- Consultas -->
            <div class="funcionalidade-card" style="background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s;">
                <h3 style="color: #4caf50; margin: 0 0 15px 0; font-size: 1.2rem;">
                    📅 Consultas
                </h3>
                <p style="color: #666; margin: 0 0 15px 0; font-size: 0.95rem;">
                    Agende e acompanhe suas consultas médicas e nutricionais.
                </p>
                <a href="/paciente/consultas" style="background: #4caf50; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block;">
                    Em breve
                </a>
            </div>

            <!-- Configurações -->
            <div class="funcionalidade-card" style="background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s;">
                <h3 style="color: #4caf50; margin: 0 0 15px 0; font-size: 1.2rem;">
                    ⚙️ Configurações
                </h3>
                <p style="color: #666; margin: 0 0 15px 0; font-size: 0.95rem;">
                    Gerencie seus dados pessoais e configurações da conta.
                </p>
                <a href="/conta" style="background: #4caf50; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block;">
                    Acessar
                </a>
            </div>
        </div>

        <!-- Informações do paciente -->
        <div class="info-paciente" style="background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #4caf50; margin: 0 0 15px 0;">
                👤 Suas Informações
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['usuario']['nome_usuario']); ?>
                </div>
                <div>
                    <strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['usuario']['email_usuario']); ?>
                </div>
                <div>
                    <strong>CPF:</strong> <?php echo htmlspecialchars($_SESSION['paciente']['cpf'] ?? 'Não informado'); ?>
                </div>
                <div>
                    <strong>NIS:</strong> <?php echo htmlspecialchars($_SESSION['paciente']['nis'] ?? 'Não informado'); ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="/public/assets/scripts/ui-effects.js"></script>
<script>
class PacienteDashboard {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadDashboardData();
    }

    setupEventListeners() {
        // Event listeners específicos do dashboard do paciente
        const cards = document.querySelectorAll('.funcionalidade-card');
        
        cards.forEach(card => {
            card.addEventListener('click', (e) => this.onCardClick(e, card));
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'button');
        });

        // Adicionar suporte a teclado
        document.addEventListener('keydown', (e) => this.onKeyDown(e));
    }

    onCardClick(e, card) {
        // Se o clique foi em um link, deixar o comportamento padrão
        if (e.target.tagName === 'A') return;
        
        // Caso contrário, navegar para o link dentro do card
        const link = card.querySelector('a[href]');
        if (link && !link.href.includes('Em breve')) {
            window.location.href = link.href;
        }
    }

    onKeyDown(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            const focusedCard = document.activeElement;
            if (focusedCard.classList.contains('funcionalidade-card')) {
                e.preventDefault();
                this.onCardClick(e, focusedCard);
            }
        }
    }

    loadDashboardData() {
        // Aqui poderia carregar dados específicos do dashboard
        this.updateLastActivity();
    }

    updateLastActivity() {
        const now = new Date();
        const activity = `Último acesso: ${now.toLocaleDateString('pt-BR')} às ${now.toLocaleTimeString('pt-BR')}`;
        
        // Adicionar informação de último acesso se houver elemento para isso
        const activityElement = document.getElementById('last-activity');
        if (activityElement) {
            activityElement.textContent = activity;
        }
    }

    showNotification(message, type = 'info') {
        // Método para mostrar notificações específicas do paciente
        console.log(`[${type.toUpperCase()}] ${message}`);
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    new PacienteDashboard();
});
</script>
