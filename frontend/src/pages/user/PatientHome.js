import React from 'react';
import { Link } from 'react-router-dom';
import './PatientHome.css';

const PatientHome = () => {
  return (
    <div className="usuario-container patient-container">
      <main className="usuario-main-content">
        <div className="usuario-header patient-header">
          <h1>
            <span role="img" aria-label="Ícone de paciente">🧑‍🦱</span> Bem-vindo ao Painel do Paciente
          </h1>
          <p>
            Acompanhe sua saúde, visualize orientações e mantenha seus dados atualizados.
          </p>
        </div>
        
        <section className="usuario-section" id="home">
          <h2>
            <span role="img" aria-label="Ícone de casa" className="section-icon">🏠</span> Início
          </h2>
          <p>
            Bem-vindo ao seu <strong>painel do paciente</strong>! Aqui você pode acompanhar sua saúde, acessar orientações e atualizar seus dados.<br />
            <span className="highlight-text">Use o menu lateral para navegar.</span>
          </p>
        </section>
        
        <section className="usuario-section" id="about">
          <h2>
            <span role="img" aria-label="Ícone de informação" className="section-icon">ℹ️</span> Sobre
          </h2>
          <p>
            Este sistema foi desenvolvido para facilitar o <strong>acompanhamento da sua saúde</strong>, centralizando informações e orientações.
          </p>
          <ul className="feature-list">
            <li>Visualize orientações dos profissionais</li>
            <li>Acompanhe seu histórico de saúde</li>
            <li>Mantenha seus dados sempre atualizados</li>
          </ul>
        </section>
        
        <section className="usuario-section" id="services">
          <h2>
            <span role="img" aria-label="Ícone de ferramentas" className="section-icon">🛠️</span> Serviços
          </h2>
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
              <p>Fale com nossa equipe para tirar dúvidas ou resolver problemas.</p>
            </div>
          </div>
        </section>
        
        <section className="usuario-section" id="contact">
          <h2>
            <span role="img" aria-label="Ícone de telefone" className="section-icon">📞</span> Contato
          </h2>
          <p>
            Precisa de suporte? Fale com nossa equipe:<br />
            <strong>Email:</strong> <a href="mailto:suporte@delimeter.com">suporte@delimeter.com</a><br />
            Ou utilize o formulário disponível no site.
          </p>
        </section>
      </main>
    </div>
  );
};

export default PatientHome;