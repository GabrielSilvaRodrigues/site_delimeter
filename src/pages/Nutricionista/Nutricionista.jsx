import React, { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import NutritionalCalculator from '../../components/NutritionalCalculator/NutritionalCalculator';
import './Nutricionista.css';

const Nutricionista = () => {
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
    <div className="usuario-container nutricionista-container">
      <main className="usuario-main-content">
        <div className="usuario-header nutricionista-header">
          <h1>🥗 Bem-vindo ao Painel do Nutricionista</h1>
          <p>Gerencie pacientes, planos alimentares e acompanhe resultados nutricionais.</p>
        </div>

        <section className="usuario-section" id="home">
          <h2>🏥 Início</h2>
          <p>
            Bem-vindo ao seu <strong>painel do nutricionista</strong>! Aqui você pode acompanhar 
            pacientes, criar planos alimentares e analisar resultados.<br />
            <span className="highlight">Use o menu lateral para navegar.</span>
          </p>
        </section>

        <section className="usuario-section" id="calculator">
          <h2>📊 Calculadora Nutricional</h2>
          <p>Utilize nossa calculadora para avaliar pacientes:</p>
          <NutritionalCalculator />
        </section>

        <section className="usuario-section" id="about">
          <h2>ℹ️ Sobre</h2>
          <p>
            Este sistema foi desenvolvido para facilitar o <strong>acompanhamento nutricional</strong>, 
            centralizando informações e otimizando o atendimento.
          </p>
          <ul className="features-list">
            <li>Gerencie dados dos pacientes</li>
            <li>Monte e acompanhe planos alimentares</li>
            <li>Ferramentas de análise nutricional</li>
            <li>Relatórios detalhados de progresso</li>
          </ul>
        </section>

        <section className="usuario-section" id="services">
          <h2>🛠️ Serviços</h2>
          <div className="services-grid">
            <div className="service-card">
              <div className="service-icon">👥</div>
              <h3>Pacientes</h3>
              <p>Gerencie sua carteira de pacientes</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📋</div>
              <h3>Planos Alimentares</h3>
              <p>Crie e monitore dietas personalizadas</p>
            </div>
            
            <div className="service-card">
              <div className="service-icon">📊</div>
              <h3>Análises</h3>
              <p>Relatórios e estatísticas nutricionais</p>
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

export default Nutricionista;