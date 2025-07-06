import React from 'react';
import './MedicoPanel.css';

const MedicoPanel = ({ user }) => {
  return (
    <div className="medico-panel">
      <div className="panel-container">
        <div className="panel-header medico-header">
          <h1>🩺 Bem-vindo ao Painel do Médico</h1>
          <p>Gerencie pacientes, visualize históricos e acesse ferramentas clínicas.</p>
        </div>

        <section className="panel-section">
          <h2>🏥 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel médico</strong>! Aqui você pode acompanhar pacientes e acessar prontuários.
          </p>
        </section>

        <section className="panel-section">
          <h2>ℹ️ Sobre</h2>
          <p>Este sistema facilita o <strong>acompanhamento clínico</strong>, centralizando informações e otimizando o atendimento.</p>
          <ul>
            <li>Gerencie dados dos pacientes</li>
            <li>Acesse históricos e prontuários</li>
            <li>Ferramentas de apoio à decisão clínica</li>
          </ul>
        </section>

        <section className="panel-section">
          <h2>🛠️ Serviços</h2>
          <div className="service-cards">
            <div className="service-card">
              <div className="service-icon">👨‍⚕️</div>
              <h3>Pacientes</h3>
              <p>Gerencie e acompanhe seus pacientes.</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📋</div>
              <h3>Prontuários</h3>
              <p>Acesse históricos clínicos e anotações.</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">🧰</div>
              <h3>Ferramentas</h3>
              <p>Utilize calculadoras e recursos médicos.</p>
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

export default MedicoPanel;