import React from 'react';
import './NutricionistaPanel.css';

const NutricionistaPanel = ({ user }) => {
  return (
    <div className="nutricionista-panel">
      <div className="panel-container">
        <div className="panel-header nutricionista-header">
          <h1>🥗 Bem-vindo ao Painel do Nutricionista</h1>
          <p>Gerencie pacientes, planos alimentares e acompanhe resultados nutricionais.</p>
        </div>

        <section className="panel-section">
          <h2>🏥 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel do nutricionista</strong>! Aqui você pode acompanhar pacientes e criar planos alimentares.
          </p>
        </section>

        <section className="panel-section">
          <h2>ℹ️ Sobre</h2>
          <p>Este sistema facilita o <strong>acompanhamento nutricional</strong>, centralizando informações e otimizando o atendimento.</p>
          <ul>
            <li>Gerencie dados dos pacientes</li>
            <li>Monte e acompanhe planos alimentares</li>
            <li>Ferramentas de análise nutricional</li>
          </ul>
        </section>

        <section className="panel-section">
          <h2>🛠️ Serviços</h2>
          <div className="service-cards">
            <div className="service-card">
              <div className="service-icon">🥗</div>
              <h3>Pacientes</h3>
              <p>Gerencie e acompanhe seus pacientes.</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📋</div>
              <h3>Planos Alimentares</h3>
              <p>Crie e acompanhe planos personalizados.</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📊</div>
              <h3>Ferramentas</h3>
              <p>Utilize calculadoras e recursos nutricionais.</p>
            </div>
          </div>
        </section>

        <section className="panel-section">
          <h2>📞 Contato</h2>
          <p>
            Precisa de suporte? Fale com nossa equipe:<br />
            <strong>Email:</strong> <a href="mailto:suporte@delimeter.com">suporte@delimeter.com</a>
          </p>
        </section>
      </div>
    </div>
  );
};

export default NutricionistaPanel;