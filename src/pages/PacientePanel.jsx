import React from 'react';
import './PacientePanel.css';

const PacientePanel = ({ user }) => {
  return (
    <div className="paciente-panel">
      <div className="panel-container">
        <div className="panel-header paciente-header">
          <h1>🧑‍🦱 Bem-vindo ao Painel do Paciente</h1>
          <p>Acompanhe sua saúde, visualize orientações e mantenha seus dados atualizados.</p>
        </div>

        <section className="panel-section">
          <h2>🏠 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel do paciente</strong>! Aqui você pode acompanhar sua saúde e acessar orientações.
          </p>
        </section>

        <section className="panel-section">
          <h2>ℹ️ Sobre</h2>
          <p>Este sistema facilita o <strong>acompanhamento da sua saúde</strong>, centralizando informações e orientações.</p>
          <ul>
            <li>Visualize orientações dos profissionais</li>
            <li>Acompanhe seu histórico de saúde</li>
            <li>Mantenha seus dados sempre atualizados</li>
          </ul>
        </section>

        <section className="panel-section">
          <h2>🛠️ Serviços</h2>
          <div className="service-cards">
            <div className="service-card">
              <div className="service-icon">🧑‍🦱</div>
              <h3>Meu Perfil</h3>
              <p>Atualize suas informações pessoais e preferências.</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📜</div>
              <h3>Histórico</h3>
              <p>Consulte seu histórico de saúde e atendimentos.</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">💬</div>
              <h3>Suporte</h3>
              <p>Fale com nossa equipe para tirar dúvidas.</p>
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

export default PacientePanel;