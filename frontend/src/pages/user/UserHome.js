import React from 'react';
import { Link } from 'react-router-dom';
import './UserHome.css';

const UserHome = () => {
  return (
    <div className="usuario-container">
      <main className="usuario-main-content">
        <div className="usuario-header">
          <h1>
            <span role="img" aria-label="Ícone de usuário">👤</span> Bem-vindo ao Sistema de Gerenciamento de Usuários
          </h1>
          <p>
            Gerencie seus dados, visualize informações e aproveite nossos serviços exclusivos.
          </p>
        </div>
        
        {/* Seção de cadastro para diferentes perfis */}
        <section className="usuario-section" id="cadastro-tipos">
          <h2>
            <span role="img" aria-label="Ícone de documento">📝</span> Entrar como:
          </h2>
          <div className="user-types">
            <Link to="/paciente" className="user-type">
              <span role="img" aria-label="Ícone de paciente" className="user-icon">🧑‍🦱</span>
              Paciente
            </Link>
            <Link to="/nutricionista" className="user-type">
              <span role="img" aria-label="Ícone de nutricionista" className="user-icon">🥗</span>
              Nutricionista
            </Link>
            <Link to="/medico" className="user-type">
              <span role="img" aria-label="Ícone de médico" className="user-icon">🩺</span>
              Médico
            </Link>
          </div>
        </section>
        
        <section className="usuario-section" id="home">
          <h2>
            <span role="img" aria-label="Ícone de casa" className="section-icon">🏠</span> Início
          </h2>
          <p>
            Bem-vindo ao seu <strong>painel de usuário</strong>! Aqui você pode acessar e gerenciar suas informações de forma simples, segura e prática.<br />
            <span className="highlight-text">Utilize o menu lateral para navegar entre as funcionalidades.</span>
          </p>
        </section>
        
        <section className="usuario-section" id="about">
          <h2>
            <span role="img" aria-label="Ícone de informação" className="section-icon">ℹ️</span> Sobre
          </h2>
          <p>
            Nosso sistema foi desenvolvido para facilitar o <strong>gerenciamento de usuários</strong>, proporcionando praticidade, segurança e autonomia.
          </p>
          <ul className="feature-list">
            <li>Atualize seus dados pessoais facilmente</li>
            <li>Visualize seu histórico de atividades</li>
            <li>Receba suporte personalizado</li>
          </ul>
        </section>
        
        <section className="usuario-section" id="services">
          <h2>
            <span role="img" aria-label="Ícone de ferramentas" className="section-icon">🛠️</span> Serviços
          </h2>
          <div className="service-cards">
            <div className="service-card">
              <div className="service-icon">👤</div>
              <h3>Perfil</h3>
              <p>Atualize suas informações pessoais e preferências.</p>
            </div>
            <div className="service-card">
              <div className="service-icon">📜</div>
              <h3>Histórico</h3>
              <p>Consulte seu histórico de acessos e atividades.</p>
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
            Precisa de ajuda? Entre em contato com nossa equipe de suporte:<br />
            <strong>Email:</strong> <a href="mailto:suporte@delimeter.com">suporte@delimeter.com</a><br />
            Ou utilize o formulário disponível no site.
          </p>
        </section>
      </main>
    </div>
  );
};

export default UserHome;