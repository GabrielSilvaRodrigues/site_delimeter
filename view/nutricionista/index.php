<div class="usuario-container" style="background: linear-gradient(120deg, #f4f4f4 60%, #e8f5e9 100%); min-height: 100vh;">
    <main class="usuario-main-content" style="max-width: 900px; margin: 0 auto; padding-bottom: 40px;">
        <div class="usuario-header" style="margin-bottom: 40px; background: linear-gradient(90deg, #4caf50 70%, #388e3c 100%); box-shadow: 0 4px 16px rgba(76,175,80,0.13); border-radius: 14px;">
            <h1 style="font-size:2.5rem; margin-bottom: 10px; letter-spacing: 1px; color: #fff; text-shadow: 1px 2px 8px #388e3c33;">
                🥗 Bem-vindo ao Painel do Nutricionista
            </h1>
            <p style="font-size:1.18rem; color:#e8f5e9; margin-bottom: 0;">
                Crie dietas, acompanhe pacientes e monitore progressos nutricionais.
            </p>
        </div>
        
        <section class="usuario-section" id="home" style="margin-bottom: 32px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #4caf5022;">
            <h2 style="font-size:1.6rem; color:#4caf50; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">🏠</span> Início
            </h2>
            <p style="font-size:1.08rem; color:#444;">
                Bem-vindo ao seu <strong>painel nutricional</strong>! Aqui você pode criar dietas personalizadas, acompanhar pacientes e analisar diários alimentares.<br>
                <span style="color:#4caf50;">Use o menu lateral para navegar pelas funcionalidades.</span>
            </p>
        </section>
        
        <section class="usuario-section" id="about" style="margin-bottom: 32px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #4caf5022;">
            <h2 style="font-size:1.6rem; color:#4caf50; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">ℹ️</span> Sobre
            </h2>
            <p style="font-size:1.08rem; color:#444;">
                Este sistema foi desenvolvido para facilitar o <strong>acompanhamento nutricional</strong>, oferecendo ferramentas completas para o cuidado alimentar.
            </p>
            <ul style="margin: 15px 0 0 20px; color:#4caf50; font-size:1.05rem; line-height:1.7;">
                <li>Crie e gerencie dietas personalizadas</li>
                <li>Monitore diários alimentares dos pacientes</li>
                <li>Acompanhe medidas antropométricas</li>
                <li>Analise progressos nutricionais</li>
            </ul>
        </section>
        
        <section class="usuario-section" id="services" style="margin-bottom: 32px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #4caf5022;">
            <h2 style="font-size:1.6rem; color:#4caf50; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">🛠️</span> Funcionalidades Nutricionais
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                
                <!-- Pacientes -->
                <a href="/nutricionista/pacientes" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8fff8; border-radius:12px; box-shadow:0 2px 10px #4caf5011; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">👥</div>
                        <h3 style="margin-bottom: 8px; color:#4caf50; font-size:1.3rem; text-align: center;">Pacientes</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Gerencie seus pacientes e acompanhe o progresso nutricional.</p>
                    </div>
                </a>

                <!-- Dietas -->
                <a href="/nutricionista/dietas" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8fff8; border-radius:12px; box-shadow:0 2px 10px #4caf5011; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">🥗</div>
                        <h3 style="margin-bottom: 8px; color:#4caf50; font-size:1.3rem; text-align: center;">Dietas</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Crie e personalize dietas para seus pacientes.</p>
                    </div>
                </a>

                <!-- Diários Alimentares -->
                <a href="/nutricionista/diarios" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8fff8; border-radius:12px; box-shadow:0 2px 10px #4caf5011; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">📔</div>
                        <h3 style="margin-bottom: 8px; color:#4caf50; font-size:1.3rem; text-align: center;">Diários</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Acompanhe os diários alimentares dos pacientes.</p>
                    </div>
                </a>

                <!-- Consultas -->
                <a href="/nutricionista/consultas" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8fff8; border-radius:12px; box-shadow:0 2px 10px #4caf5011; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">📅</div>
                        <h3 style="margin-bottom: 8px; color:#4caf50; font-size:1.3rem; text-align: center;">Consultas</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Agende e gerencie consultas nutricionais.</p>
                    </div>
                </a>

                <!-- Medidas Antropométricas -->
                <a href="/nutricionista/medidas" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8fff8; border-radius:12px; box-shadow:0 2px 10px #4caf5011; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">📏</div>
                        <h3 style="margin-bottom: 8px; color:#4caf50; font-size:1.3rem; text-align: center;">Medidas</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Registre e acompanhe medidas antropométricas.</p>
                    </div>
                </a>

                <!-- Calculadora Nutricional -->
                <a href="/calculo-nutricional" style="text-decoration: none; color: inherit;">
                    <div class="funcionalidade-card" style="background:#f8fff8; border-radius:12px; box-shadow:0 2px 10px #4caf5011; padding:20px; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size:3em; margin-bottom:12px; text-align: center;">🧮</div>
                        <h3 style="margin-bottom: 8px; color:#4caf50; font-size:1.3rem; text-align: center;">Calculadora</h3>
                        <p style="margin:0; color:#555; text-align:center; line-height: 1.5;">Ferramentas de cálculo nutricional e análise corporal.</p>
                    </div>
                </a>
            </div>
        </section>
        
        <section class="usuario-section" id="contact" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #4caf5022;">
            <h2 style="font-size:1.6rem; color:#4caf50; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <span style="font-size:1.3em;">📞</span> Contato
            </h2>
            <p style="font-size:1.08rem; color:#444;">
                Precisa de suporte ou tem dúvidas sobre o sistema?<br>
                <strong>Email:</strong> <a href="mailto:suporte@delimeter.com" style="color:#4caf50; text-decoration:underline;">suporte@delimeter.com</a><br>
                <strong>WhatsApp:</strong> <a href="https://wa.me/5511999999999" style="color:#4caf50; text-decoration:underline;">(11) 99999-9999</a>
            </p>
        </section>
    </main>
</div>

<!-- Incluir scripts das classes -->
<script src="/public/assets/scripts/ui-effects.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Painel do nutricionista carregado');
});
</script>
