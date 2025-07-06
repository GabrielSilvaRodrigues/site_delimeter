import React from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import './Usuario.css';

const Usuario = () => {
  const { user } = useAuth();

  return (
    <div className="usuario-container">
      <main className="usuario-main-content">
        <div className="usuario-header">
          <h1>👤 Bem-vindo ao Sistema de Gerenciamento de Usuários</h1>
          <p>Gerencie seus dados, visualize informações e aproveite nossos serviços exclusivos.</p>
        </div>

        {/* Seção de cadastro para diferentes perfis */}
        <section className="usuario-section" id="cadastro-tipos">
          <h2>📝 Entrar como:</h2>
          <div className="profile-cards">
            <Link to="/paciente" className="profile-card paciente">
              <div className="profile-icon">🧑‍🦱</div>
              <h3>Paciente</h3>
              <p>Acompanhe sua saúde e bem-estar</p>
            </Link>
            
            <Link to="/nutricionista" className="profile-card nutricionista">
              <div className="profile-icon">🥗</div>
              <h3>Nutricionista</h3>
              <p>Gerencie pacientes e planos alimentares</p>
            </Link>
            
            <Link to="/medico" className="profile-card medico">
              <div className="profile-icon">🩺</div>
              <h3>Médico</h3>
              <p>Atenda pacientes e gerencie prontuários</p>
            </Link>
          </div>
        </section>

        <section className="usuario-section" id="home">
          <h2>🏠 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel de usuário</strong>! Aqui você pode acessar e 
            gerenciar suas informações de forma simples, segura e prática.<br />
            <span className="highlight">Utilize o menu lateral para navegar entre as funcionalidades.</span>
          </p>
        </section>

        <section className="usuario-section" id="about">
          <h2>ℹ️ Sobre</h2>
          <p>
            Nosso sistema foi desenvolvido para facilitar o <strong>gerenciamento de usuários</strong>, 
            proporcionando praticidade, segurança e autonomia.
          </p>
          <ul className="features-list">
            <li>Acesso personalizado por tipo de usuário</li>
            <li>Interface intuitiva e responsiva</li>
            <li>Segurança de dados garantida</li>
            <li>Suporte técnico disponível</li>
          </ul>
        </section>

        <section className="usuario-section" id="services">
          <h2>🛠️ Serviços</h2>
          <div className="services-grid">
            <div className="service-card">
              <div className="service-icon">👤</div>
              <h3>Perfil</h3>
              <p>Gerencie suas informações pessoais</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📊</div>
              <h3>Relatórios</h3>
              <p>Visualize estatísticas e dados</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">🔧</div>
              <h3>Configurações</h3>
              <p>Personalize sua experiência</p>
            </div>
          </div>
        </section>

        <section className="usuario-section" id="contact">
          <h2>📞 Contato</h2>
          <p>
            Precisa de suporte? Fale com nossa equipe:<br />
            <strong>Email:</strong> 
            <a href="mailto:suporte@delimeter.com">suporte@delimeter.com</a><br />
            Ou utilize o formulário disponível no site.
          </p>
        </section>
      </main>
    </div>
  );
};

export default Usuario;