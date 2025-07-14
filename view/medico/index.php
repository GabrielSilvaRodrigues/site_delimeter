<div class="usuario-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e0f7fa 100%); min-height: 100vh;">
    <main class="usuario-main-content" style="max-width: 900px; margin: 0 auto; padding-bottom: 40px;">
        <div class="usuario-header" style="margin-bottom: 40px; background: linear-gradient(90deg, #1976d2 70%, #1565c0 100%); box-shadow: 0 4px 16px rgba(25,118,210,0.13); border-radius: 14px;">
            <h1 style="font-size:2.5rem; margin-bottom: 10px; letter-spacing: 1px; color: #fff; text-shadow: 1px 2px 8px #1565c033;">
                🩺 Bem-vindo ao Painel do Médico
            </h1>
            <p style="font-size:1.18rem; color:#e0f7fa; margin-bottom: 0;">
                Gerencie pacientes, visualize históricos e acesse ferramentas clínicas.
            </p>
        </div>
        <section class="usuario-section" id="home" style="margin-bottom: 32px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #1976d222;">
            <h2 style="font-size:1.6rem; color:#1976d2; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">🏥</span> Início
            </h2>
            <p style="font-size:1.08rem; color:#444;">
                Bem-vindo ao seu <strong>painel médico</strong>! Aqui você pode acompanhar pacientes, acessar prontuários e utilizar ferramentas clínicas.<br>
                <span style="color:#1976d2;">Use o menu lateral para navegar.</span>
            </p>
        </section>
        <section class="usuario-section" id="about" style="margin-bottom: 32px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #1976d222;">
            <h2 style="font-size:1.6rem; color:#1976d2; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">ℹ️</span> Sobre
            </h2>
            <p style="font-size:1.08rem; color:#444;">
                Este sistema foi desenvolvido para facilitar o <strong>acompanhamento clínico</strong>, centralizando informações e otimizando o atendimento.
            </p>
            <ul style="margin: 15px 0 0 20px; color:#1976d2; font-size:1.05rem; line-height:1.7;">
                <li>Gerencie dados dos pacientes</li>
                <li>Acesse históricos e prontuários</li>
                <li>Ferramentas de apoio à decisão clínica</li>
            </ul>
        </section>
        <section class="usuario-section" id="services" style="margin-bottom: 32px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #1976d222;">
            <h2 style="font-size:1.6rem; color:#1976d2; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">🛠️</span> Funcionalidades Médicas
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                
                <!-- Pacientes -->
                <a href="/medico/pacientes" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8faff; border-radius:12px; box-shadow:0 2px 10px #1976d211; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">👨‍⚕️</div>
                        <h3 style="margin-bottom: 8px; color:#1976d2; font-size:1.3rem; text-align: center;">Pacientes</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Gerencie e acompanhe seus pacientes, visualize históricos médicos.</p>
                    </div>
                </a>

                <!-- Prontuários -->
                <a href="/medico/prontuarios" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8faff; border-radius:12px; box-shadow:0 2px 10px #1976d211; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">📋</div>
                        <h3 style="margin-bottom: 8px; color:#1976d2; font-size:1.3rem; text-align: center;">Prontuários</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Acesse e edite prontuários médicos, históricos clínicos.</p>
                    </div>
                </a>

                <!-- Receitas -->
                <a href="/medico/receitas" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8faff; border-radius:12px; box-shadow:0 2px 10px #1976d211; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">💊</div>
                        <h3 style="margin-bottom: 8px; color:#1976d2; font-size:1.3rem; text-align: center;">Receitas</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Crie e gerencie receitas médicas para seus pacientes.</p>
                    </div>
                </a>

                <!-- Consultas -->
                <a href="/medico/consultas" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8faff; border-radius:12px; box-shadow:0 2px 10px #1976d211; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">🩺</div>
                        <h3 style="margin-bottom: 8px; color:#1976d2; font-size:1.3rem; text-align: center;">Consultas</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Agende e gerencie consultas com seus pacientes.</p>
                    </div>
                </a>

                <!-- Validar Dietas -->
                <a href="/medico/validar-dietas" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8faff; border-radius:12px; box-shadow:0 2px 10px #1976d211; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">🥗</div>
                        <h3 style="margin-bottom: 8px; color:#1976d2; font-size:1.3rem; text-align: center;">Validar Dietas</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Revise e valide dietas prescritas por nutricionistas.</p>
                    </div>
                </a>

                <!-- Calculadora Nutricional -->
                <a href="/calculo-nutricional" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8faff; border-radius:12px; box-shadow:0 2px 10px #1976d211; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">🧮</div>
                        <h3 style="margin-bottom: 8px; color:#1976d2; font-size:1.3rem; text-align: center;">Calculadora</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Ferramentas de cálculo nutricional e análise corporal.</p>
                    </div>
                </a>
            </div>
        </section>
        <section class="usuario-section" id="contact" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #1976d222;">
            <h2 style="font-size:1.6rem; color:#1976d2; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">📞</span> Contato
            </h2>
            <p style="font-size:1.08rem; color:#444;">
                Precisa de suporte? Fale com nossa equipe:<br>
                <strong>Email:</strong> <a href="mailto:suporte@delimeter.com" style="color:#1976d2; text-decoration:underline;">suporte@delimeter.com</a><br>
                Ou utilize o formulário disponível no site.
            </p>
        </section>
    </main>
</div>

<!-- Incluir scripts das classes -->
<script src="/public/assets/scripts/ui-effects.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Painel do médico carregado');
});
</script>
