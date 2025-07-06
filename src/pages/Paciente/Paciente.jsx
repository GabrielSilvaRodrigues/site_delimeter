import React, { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import NutritionalCalculator from '../../components/NutritionalCalculator/NutritionalCalculator';
import './Paciente.css';

const Paciente = () => {
  const { user } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (!user) {
      navigate('/usuario/login');
    }
  }, [user, navigate]);

  if (!user) {
    return <div>Carregando...</div>;
  }

  return (
    <div className="usuario-container paciente-container">
      <main className="usuario-main-content">
        <div className="usuario-header paciente-header">
          <h1>🧑‍🦱 Bem-vindo ao Painel do Paciente</h1>
          <p>Acompanhe sua saúde, visualize orientações e mantenha seus dados atualizados.</p>
        </div>

        <section className="usuario-section" id="home">
          <h2>🏠 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel do paciente</strong>! Aqui você pode acompanhar 
            sua saúde, acessar orientações e atualizar seus dados.<br />
            <span className="highlight">Use o menu lateral para navegar.</span>
          </p>
        </section>

        <section className="usuario-section" id="calculator">
          <h2>📊 Calculadora Nutricional</h2>
          <p>Calcule seu gasto energético e descubra suas necessidades nutricionais:</p>
          <NutritionalCalculator />
        </section>

        <section className="usuario-section" id="about">
          <h2>ℹ️ Sobre</h2>
          <p>
            Este sistema foi desenvolvido para facilitar o <strong>acompanhamento da sua saúde</strong>, 
            centralizando informações e orientações.
          </p>
          <ul className="features-list">
            <li>Visualize orientações dos profissionais</li>
            <li>Acompanhe seu histórico de saúde</li>
            <li>Mantenha seus dados sempre atualizados</li>
            <li>Acesse calculadoras nutricionais</li>
          </ul>
        </section>

        <section className="usuario-section" id="services">
          <h2>🛠️ Serviços</h2>
          <div className="services-grid">
            <div className="service-card">
              <div className="service-icon">🧑‍🦱</div>
              <h3>Meu Perfil</h3>
              <p>Gerencie suas informações pessoais</p>
              <Link to="/conta" className="service-link">Acessar</Link>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📊</div>
              <h3>Cálculos Nutricionais</h3>
              <p>Calcule IMC, GET e macronutrientes</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📋</div>
              <h3>Histórico</h3>
              <p>Visualize seu histórico de saúde</p>
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

export default Paciente;