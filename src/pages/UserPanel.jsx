import React from 'react';
import './UserPanel.css';

const UserPanel = ({ user }) => {
  return (
    <div className="user-panel">
      <div className="panel-container">
        <div className="panel-header">
          <h1>👤 Bem-vindo ao Sistema</h1>
          <p>Olá, {user.nome_usuario}! Gerencie seus dados e acesse nossos serviços.</p>
        </div>

        <section className="panel-section">
          <h2>🏠 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel de usuário</strong>! Aqui você pode acessar e gerenciar suas informações.
          </p>
        </section>

        <section className="panel-section">
          <h2>📝 Entrar como:</h2>
          <div className="role-cards">
            <a href="/paciente" className="role-card paciente">
              <div className="role-icon">🧑‍🦱</div>
              <h3>Paciente</h3>
              <p>Acompanhe sua saúde e receba orientações</p>
            </a>
            
            <a href="/nutricionista" className="role-card nutricionista">
              <div className="role-icon">🥗</div>
              <h3>Nutricionista</h3>
              <p>Gerencie pacientes e planos alimentares</p>
            </a>
            
            <a href="/medico" className="role-card medico">
              <div className="role-icon">🩺</div>
              <h3>Médico</h3>
              <p>Acompanhe pacientes e prontuários</p>
            </a>
          </div>
        </section>

        <section className="panel-section">
          <h2>🛠️ Serviços</h2>
          <div className="service-cards">
            <div className="service-card">
              <div className="service-icon">👤</div>
              <h3>Meu Perfil</h3>
              <p>Atualize suas informações pessoais</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📊</div>
              <h3>Histórico</h3>
              <p>Visualize seu histórico de atividades</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">💬</div>
              <h3>Suporte</h3>
              <p>Fale com nossa equipe</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
};

export default UserPanel;